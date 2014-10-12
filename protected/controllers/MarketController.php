<?php

class MarketController extends WechatManagerController
{
    public function actionGift()
    {
        $this->layout = '/layouts/memberList';
        $dataProvider = new CActiveDataProvider('GiftModel', array(
            'criteria' => array(
                'order' => 'id DESC',
            ),
            //'pagination' => false,
            'pagination' => array(
                'pageSize' => Page::SIZE,
                'pageVar' => 'page'
            ),
        ));
        $this->render('gift', array('data' => $dataProvider->getData(), 'pages' => $dataProvider->getPagination()));
    }

    public function actionGiftCreate()
    {
        $type = '';
        $model = new GiftModel();
        if (isset($_POST['GiftModel'])) {
            $model->attributes = $_POST['GiftModel'];
            $type = $model->type;
            if ($model->validate()) {
                $model->save();
                switch ($type) {
                    case GiftModel::TYPE_KEYWORDS:
                        $keywords = $_POST['GiftModel']['keyword'];
                        $isAccurate = $_POST['GiftModel']['isAccurate'];
                        $keywordsArray = explode(',', $keywords);
                        foreach ($keywordsArray as $k) {
                            $keywordsModel = new KeywordsModel();
                            $keywordsModel->replayId = $model->id;
                            $keywordsModel->type = GiftModel::GIFT_TYPE;
                            $keywordsModel->isAccurate = $isAccurate;
                            $keywordsModel->name = $k;
                            $keywordsModel->wechatId = $this->wechatInfo->id;
                            $keywordsModel->save();
                        }
                        break;
                    case GiftModel::TYPE_MENU:
                        $action = $_POST['GiftModel']['action'];
                        $menuAction = MenuactionModel::model()->find('type=:type and action=:action',
                            array(':type' => GiftModel::GIFT_TYPE, ':action' => $action));
                        if ($menuAction) {
                            ShowMessage::error('菜单动作已经被应用了');
                        }
                        $menuActionModel = new MenuactionModel();
                        $menuActionModel->wechatId = $this->wechatInfo->id;
                        $menuActionModel->type = GiftModel::GIFT_TYPE;
                        $menuActionModel->action = $action;
                        $menuActionModel->responseId = $model->id;
                        $menuActionModel->save();
                        break;
                }
                ShowMessage::success('添加成功', Yii::app()->createUrl('market/gift'));
            }
        }
        $this->render('giftCreate', array('model' => $model, 'type' => $type ? $type : GiftModel::TYPE_KEYWORDS));
    }

    public function actionGiftUpdate($id)
    {
        $keyword = $common = '';
        $model = GiftModel::model()->findByPk($id);
        switch ($model->type) {
            //获取关联表数据
            case GiftModel::TYPE_KEYWORDS:
                $keywords = KeywordsModel::model()->findAll('type=:type and replayId=:replayId',
                    array(':type' => GiftModel::GIFT_TYPE, ':replayId' => $id));
                foreach ($keywords as $k) {
                    $oldKeywords[] = $k->name;
                    $oldIsAccurate = $k->isAccurate;
                    $isAccurate = $k->isAccurate;
                    $keyword .= $common . $k->name;
                    $common = ',';
                }
                $model->keyword = $keyword;
                $model->isAccurate = $isAccurate;
                break;
            case GiftModel::TYPE_MENU:
                $action = MenuactionModel::model()->find('type=:type and responseId=:responseId',
                    array(':type' => GiftModel::GIFT_TYPE, ':responseId' => $id));
                $oldAction = $model->action = $action->action;
                break;
        }
        if (isset($_POST['GiftModel'])) {
            $model->attributes = $_POST['GiftModel'];
            if ($model->validate()) {
                switch ($model->type) {
                    //根据活动类型更新不同关联表
                    case GiftModel::TYPE_KEYWORDS:
                        $keywordsArray = explode(',', $_POST['GiftModel']['keyword']);
                        $keywordsAdd = array_unique(array_merge($oldKeywords, $keywordsArray));
                        $arrayDel = array_diff($keywordsAdd, $keywordsArray); //删除了的关键字
                        $arrayAdd = array_diff($keywordsAdd, $oldKeywords); //添加的关键字
                        foreach ($arrayAdd as $k) {
                            //新加关键词
                            $keywordsModel = new KeywordsModel();
                            $keywordsModel->replayId = $id;
                            $keywordsModel->name = $k;
                            $keywordsModel->isAccurate = $isAccurate;
                            $keywordsModel->wechatId = $this->wechatInfo->id;
                            $keywordsModel->type = GiftModel::GIFT_TYPE;
                            $keywordsModel->save();
                        }
                        foreach ($arrayDel as $k) {
                            //删除的关键词
                            $keywordsModel = KeywordsModel::model()->find('replayId=:replayId and name=:name', array(':name' => $k, ':replayId' => $id));
                            $keywordsModel->delete();
                        }
                        if ($oldIsAccurate != $isAccurate) {
                            KeywordsModel::model()->updateAll(array('isAccurate' => $isAccurate), 'replayId=:replayId', array(':replayId' => $id));
                        }
                        //更新是否精准匹配字段
                        break;
                    case GiftModel::TYPE_MENU:
                        $newAction = $_POST['GiftModel']['action'];
                        if ($oldAction != $newAction) {
                            //检测menu action是否被其他使用
                            $actionExist = MenuactionModel::model()->find('wechatId=:wechatId and action=:action', array(':wechatId' => $this->wechatInfo->id, ':action' => $newAction));
                            if ($actionExist) {
                                ShowMessage::error('此菜单动作已经被使用了');
                            }
                            $actionModel = MenuactionModel::model()->find('type=:type and action=:action', array(':type' => GiftModel::GIFT_TYPE, ':action' => $oldAction));
                            $actionModel->action = $newAction;
                            $actionModel->save();
                        }
                        break;
                }
                $model->save();
                ShowMessage::success('编辑成功', Yii::app()->createUrl('market/gift'));
            }
        }
        $this->render('giftUpdate', array('model' => $model, 'type' => $model->type));
    }

    public function actionGiftStart($id)
    {
        $model = GiftModel::model()->findByPk($id);
        $model->status = 1;
        $model->save();
        ShowMessage::success('已开启');
    }

    public function actionGiftStop($id)
    {
        $model = GiftModel::model()->findByPk($id);
        $model->status = 0;
        $model->save();
        ShowMessage::success('已停止');
    }

    public function actionGiftDelete($id)
    {
        $model = GiftModel::model()->findByPk($id);
        GiftcodeModel::model()->deleteAll('giftId=:giftId', array(':giftId' => $id));
        //删除关键字或者menu action
        switch ($model->type) {
            case GiftModel::TYPE_KEYWORDS:
                KeywordsModel::model()->deleteAll('replayId=:replayId and type=:type', array(':replayId' => $id, ':type' => GiftModel::GIFT_TYPE));
                break;
            case GiftModel::TYPE_MENU:
                MenuactionModel::model()->deleteAll('responseId=:responseId and type=:type', array(':responseId' => $id, ':type' => GiftModel::GIFT_TYPE));
                break;
        }
        $model->delete();
        ShowMessage::success('删除成功', Yii::app()->createUrl('market/gift'));
    }

    public function actionGiftCodes($id)
    {
        $this->layout = '/layouts/memberList';
        $dataProvider = new CActiveDataProvider('GiftCodeModel', array(
            'criteria' => array(
                'condition' => 'giftId=' . $id,
                'order' => 'id DESC',
            ),
            //'pagination' => false,
            'pagination' => array(
                'pageSize' => Page::SIZE,
                'pageVar' => 'page'
            ),
        ));
        $this->render('giftCode', array('data' => $dataProvider->getData(), 'pages' => $dataProvider->getPagination(), 'giftId' => $id));
    }

    public function actionCodeImport()
    {
        $giftId = Yii::app()->request->getParam('giftId');
        $file = $_FILES;
        if ($file && $giftId) {
            $tmpFile = "upload/" . $_FILES["file"]["name"];
            if (file_exists($tmpFile)) {
                @unlink($_FILES["file"]["name"]);
            } else {
                move_uploaded_file($_FILES["file"]["tmp_name"], $tmpFile);
            }
            $handle = @fopen($tmpFile, "r");
            if ($handle) {
                while (!feof($handle)) {
                    $code = fgets($handle, 4096);
                    //入库
                    $CodeModel = new GiftcodeModel();
                    $CodeModel->giftId=$giftId;
                    $CodeModel->code = trim($code);
                    $CodeModel->save();
                }
                fclose($handle);
            }
            @unlink($tmpFile);
            $msg = "导入成功!";
        } else {
            $msg = '提交错误';
        }
        echo $msg;
    }
    // Uncomment the following methods and override them if needed
    /*
    public function filters()
    {
        // return the filter configuration for this controller, e.g.:
        return array(
            'inlineFilterName',
            array(
                'class'=>'path.to.FilterClass',
                'propertyName'=>'propertyValue',
            ),
        );
    }

    public function actions()
    {
        // return external action classes, e.g.:
        return array(
            'action1'=>'path.to.ActionClass',
            'action2'=>array(
                'class'=>'path.to.AnotherActionClass',
                'propertyName'=>'propertyValue',
            ),
        );
    }
    */
}