<?php

class MarketController extends WechatManagerController
{
    public function actionGift()
    {
        $type = Yii::app()->request->getParam('type');
        $type = $type ? $type : GiftModel::TYPE_KEYWORDS;
        switch ($type) {
            case GiftModel::TYPE_KEYWORDS:
                $with = 'gift_keywords';
                $whereType = "and t.type='" . GiftModel::TYPE_KEYWORDS . "'
                and gift_keywords.type='" . GiftModel::GIFT_TYPE . "'";
                break;
            case GiftModel::TYPE_MENU:
                $with = 'gift_menuaction';
                $whereType = "and t.type='" . GiftModel::TYPE_MENU . "'
                and gift_menuaction.type='" . GiftModel::GIFT_TYPE . "'";
                break;
        }
        $this->layout = '/layouts/memberList';
        $dataProvider = new CActiveDataProvider('GiftModel', array(
            'criteria' => array(
                'order' => 't.id DESC',
                'with' => array($with),
                'condition' => "t.wechatId = {$this->wechatInfo->id} $whereType",
                'together' => true
            ),
            //'pagination' => false,
            'pagination' => array(
                'pageSize' => Page::SIZE,
                'pageVar' => 'page'
            ),
        ));
        $this->render('gift', array('data' => $dataProvider->getData(), 'pages' => $dataProvider->getPagination(),
            'type' => $type, 'wechatInfo' => $this->wechatInfo));
    }

    public function actionGiftCreate()
    {
        $type = Yii::app()->request->getParam('type');
        $type = $type ? $type : GiftModel::TYPE_KEYWORDS;
        $model = new GiftModel();
        if (isset($_POST['GiftModel'])) {
            $model->type = $type;
            $model->attributes = $_POST['GiftModel'];
            $model->wechatId = $this->wechatInfo->id;
            if ($model->validate()) {
                $model->save();
                //创建礼包码表
                $result = GiftModel::model()->createCodeTable($this->wechatInfo->id);
                switch ($type) {
                    case GiftModel::TYPE_KEYWORDS:
                        $keywords = $_POST['GiftModel']['keywords'];
                        $isAccurate = $_POST['GiftModel']['isAccurate'];
                        $keywordsArray = explode(',', $keywords);
                        foreach ($keywordsArray as $k) {
                            $keywordsModel = new KeywordsModel();
                            $keywordsModel->responseId = $model->id;
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
                if ($result == GiftModel::TABLE_CREATE_FAILED) {
                    ShowMessage::error('创建异常，请重新编辑此信息', Yii::app()->createUrl('market/gift', array('type' => $type)));
                } else {
                    ShowMessage::success('添加成功', Yii::app()->createUrl('market/gift', array('type' => $type)));
                }

            }
        }
        Yii::app()->clientScript->scriptMap['jquery.js'] = false;
        $this->render('giftCreate', array('model' => $model, 'type' => $type ? $type : GiftModel::TYPE_KEYWORDS,
            'wechatId' => $this->wechatInfo->id, 'responseId' => 0));
    }

    public function actionGiftUpdate($id)
    {
        $keyword = $common = '';
        $model = GiftModel::model()->findByPk($id);
        switch ($model->type) {
            //获取关联表数据
            case GiftModel::TYPE_KEYWORDS:
                $keywords = KeywordsModel::model()->findAll('type=:type and responseId=:responseId',
                    array(':type' => GiftModel::GIFT_TYPE, ':responseId' => $id));
                foreach ($keywords as $k) {
                    $oldKeywords[] = $k->name;
                    $oldIsAccurate = $k->isAccurate;
                    $isAccurate = $k->isAccurate;
                    $keyword .= $common . $k->name;
                    $common = ',';
                }
                $model->keywords = $keyword;
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
                        $keywordsArray = explode(',', $_POST['GiftModel']['keywords']);
                        $keywordsAdd = array_unique(array_merge($oldKeywords, $keywordsArray));
                        $arrayDel = array_diff($keywordsAdd, $keywordsArray); //删除了的关键字
                        $arrayAdd = array_diff($keywordsAdd, $oldKeywords); //添加的关键字
                        $arrayAlive = array_diff($oldKeywords, $arrayAdd); //没改变的
                        $newIsAccurate = $_POST['GiftModel']['isAccurate'];
                        if (($isAccurate != $newIsAccurate) && $arrayAlive) {
                            //是否精准匹配改变了
                            foreach ($arrayAlive as $name) {
                                $keywordsModel = KeywordsModel::model()->find('name=:name', array(':name' => $name));
                                $keywordsModel->isAccurate = $newIsAccurate;
                                $keywordsModel->save();
                            }
                        }
                        foreach ($arrayAdd as $k) {
                            //新加关键词
                            $keywordsModel = new KeywordsModel();
                            $keywordsModel->responseId = $id;
                            $keywordsModel->name = $k;
                            $keywordsModel->isAccurate = $newIsAccurate;
                            $keywordsModel->wechatId = $this->wechatInfo->id;
                            $keywordsModel->type = GiftModel::GIFT_TYPE;
                            $keywordsModel->save();
                        }
                        foreach ($arrayDel as $k) {
                            //删除的关键词
                            $keywordsModel = KeywordsModel::model()->find('responseId=:responseId and name=:name', array(':name' => $k, ':responseId' => $id));
                            $keywordsModel->delete();
                        }
                        if ($oldIsAccurate != $isAccurate) {
                            KeywordsModel::model()->updateAll(array('isAccurate' => $isAccurate), 'responseId=:responseId', array(':responseId' => $id));
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
                //更新code表，防止礼包时礼包码表未创建成功问题
                $result = GiftModel::model()->createCodeTable($this->wechatInfo->id);
                if ($result == GiftModel::TABLE_CREATE_FAILED) {
                    ShowMessage::error('数据异常，请再次编辑一下');
                } else {
                    ShowMessage::success('编辑成功', Yii::app()->createUrl('market/gift', array('type' => $model->type)));
                }

            }
        }
        Yii::app()->clientScript->scriptMap['jquery.js'] = false;
        $this->render('giftUpdate', array('model' => $model, 'type' => $model->type, 'wechatId' => $this->wechatInfo->id, 'responseId' => $id));
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
        $codeTable = sprintf(GiftModel::CREATE_CODE_TABLE_NAME,$this->wechatInfo->id);
        GiftCodeModel::model($codeTable)->deleteAll('giftId=:giftId', array(':giftId' => $id));
        //删除关键字或者menu action
        switch ($model->type) {
            case GiftModel::TYPE_KEYWORDS:
                KeywordsModel::model()->deleteAll('responseId=:responseId and type=:type', array(':responseId' => $id, ':type' => GiftModel::GIFT_TYPE));
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
        $codeTable = sprintf(GiftModel::CREATE_CODE_TABLE_NAME,$this->wechatInfo->id);
        $whereSql = 'giftId='.$id;
        $count = Yii::app()->db->createCommand('SELECT COUNT(*) FROM '.$codeTable.' where '.$whereSql)->queryScalar();
        $sql = 'SELECT * FROM '.$codeTable.' where '.$whereSql;
        $dataProvider = new CSqlDataProvider($sql, array(
            'totalItemCount' => $count,
            'sort' => array(
                'attributes' => array(
                    'name',
                )
            ),
            'pagination' => array(
                'pageSize' => Page::SIZE
            )
        ));
        $this->render('giftCode', array('data' => $dataProvider->getData(), 'pages' => $dataProvider->getPagination(), 'giftId' => $id));
    }

    public function actionCodeImport()
    {
        set_time_limit(0);
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
                    $tableName = sprintf(GiftModel::CREATE_CODE_TABLE_NAME, $this->wechatInfo->id);
                    $CodeModel = new GiftCodeModel($tableName);
                    $CodeModel->giftId = $giftId;
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