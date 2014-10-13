<?php

class ManagerController extends WechatManagerController
{
    public function actionIndex()
    {
        $this->render('index');
    }

    public function actionSubscribeReplay()
    {
        $type = Yii::app()->request->getParam('type');
        $subscribeInfo = SubscribereplayModel::model()->find('wechatId=:wechatId', array(":wechatId" => $this->wechatInfo->id));
        if ($type) {
            switch ($type) {
                case ImagetextreplayModel::IMAGE_TEXT_REPLAY_TYPE:
                    $model = ImagetextreplayModel::model()->find('wechatId=:wechatId and type=:type', array(':wechatId' => $this->wechatInfo->id, ":type" => SubscribereplayModel::SUBSCRIBE_TYPE));
                    $model = $model ? $model : new ImagetextreplayModel();
                    break;
                case TextreplayModel::TEXT_REPLAY_TYPE:
                    $model = TextreplayModel::model()->find('wechatId=:wechatId and type=:type', array(':wechatId' => $this->wechatInfo->id, ":type" => SubscribereplayModel::SUBSCRIBE_TYPE));
                    $model = $model ? $model : new TextreplayModel();
                    break;
            }
        } else {
            $type = $type ? $type : TextreplayModel::TEXT_REPLAY_TYPE;
            if ($subscribeInfo) {
                switch ($subscribeInfo->type) {
                    case TextreplayModel::TEXT_REPLAY_TYPE:
                        if ($type == TextreplayModel::TEXT_REPLAY_TYPE) {
                            $model = TextreplayModel::model()->findByPk($subscribeInfo->responseId);
                        } else {
                            $textModel = ImagetextreplayModel::model()->findByPk($subscribeInfo->responseId);
                            $model = $textModel ? $textModel : new ImagetextreplayModel();
                        }
                        break;
                    case ImagetextreplayModel::IMAGE_TEXT_REPLAY_TYPE:
                        if ($type == ImagetextreplayModel::IMAGE_TEXT_REPLAY_TYPE) {
                            $model = ImagetextreplayModel::model()->findByPk($subscribeInfo->responseId);
                        } else {
                            $imageTextModel = ImagetextreplayModel::model()->findByPk($subscribeInfo->responseId);
                            $model = $imageTextModel ? $imageTextModel : new ImagetextreplayModel();
                        }
                        $type = ImagetextreplayModel::IMAGE_TEXT_REPLAY_TYPE;
                        break;
                }
            } else {
                if ($type == TextreplayModel::TEXT_REPLAY_TYPE) {
                    $model = new TextreplayModel();
                } else {
                    $model = new ImagetextreplayModel();
                }
            }
        }
        if (isset($_POST['TextreplayModel']) || isset($_POST['ImagetextreplayModel'])) {
            if ($type == TextreplayModel::TEXT_REPLAY_TYPE) {
                $model->attributes = $_POST['TextreplayModel'];
            } else {
                $model->attributes = $_POST['ImagetextreplayModel'];
            }
            $model->type = SubscribereplayModel::SUBSCRIBE_TYPE;
            $model->wechatId = $this->wechatInfo->id;
            if ($model->validate()) {
                $model->save();
                //如果新加，添加数据到subscribe表反之更新
                if (isset($subscribeInfo)) {
                    $subscribeInfo->type = $type;
                    $subscribeInfo->responseId = $model->id;
                    $subscribeInfo->save();
                } else {
                    $subscribeModel = new SubscribereplayModel();
                    $subscribeModel->responseId = $model->id;
                    $subscribeModel->type = $type;
                    $subscribeModel->wechatId = $this->wechatInfo->id;
                    $subscribeModel->save();
                }
                ShowMessage::success('添加成功！');
            }
        }
        $this->render('subscribeReplay', array('model' => $model, 'type' => $type));
    }

    public function actionKeyWords()
    {
        $this->layout = '/layouts/memberList';
        $type = Yii::app()->request->getParam('type', TextreplayModel::TEXT_REPLAY_TYPE);
        switch ($type) {
            case TextreplayModel::TEXT_REPLAY_TYPE:
                $dataProvider = new CActiveDataProvider('TextreplayModel', array(
                    'criteria' => array(
                        'order' => 'id DESC',
                        'with' => array('textreplay_keywords'),
                        'condition' => "t.wechatId = {$this->wechatInfo->id} and t.type='" . SubscribereplayModel::KEYWORDS_TYPE . "'"
                    ),
                    //'pagination' => false,
                    'pagination' => array(
                        'pageSize' => Page::SIZE,
                        'pageVar' => 'page'
                    ),
                ));
                $view = 'textKeyWords';
                break;
            case ImagetextreplayModel::IMAGE_TEXT_REPLAY_TYPE:
                $dataProvider = new CActiveDataProvider('ImagetextreplayModel', array(
                    'criteria' => array(
                        'with' => array('imagetextreplay_keywords'),
                        'condition' => "t.wechatId = {$this->wechatInfo->id} and t.type='" . SubscribereplayModel::KEYWORDS_TYPE . "'",
                        'order' => 'id DESC',
                    ),
                    //'pagination' => false,
                    'pagination' => array(
                        'pageSize' => Page::SIZE,
                        'pageVar' => 'page'
                    ),
                ));
                $view = 'imageTextKeyWords';
                break;
        }
        $this->render($view, array('data' => $dataProvider->getData(), 'pages' => $dataProvider->getPagination()));
    }

    public function actionKeyWordsCreate()
    {
        $type = Yii::app()->request->getParam('type');
        switch ($type) {
            case ImagetextreplayModel::IMAGE_TEXT_REPLAY_TYPE:
                $model = new ImagetextreplayModel();
                break;
            case TextreplayModel::TEXT_REPLAY_TYPE:
                $model = new TextreplayModel();
                break;
        }
        if (isset($_POST['ImagetextreplayModel']) || isset($_POST['TextreplayModel'])) {
            if (isset($_POST['ImagetextreplayModel'])) {
                $model->attributes = $_POST['ImagetextreplayModel'];
                $keywords = $_POST['ImagetextreplayModel']['keywords'];
                $isAccurate = $_POST['ImagetextreplayModel']['isAccurate'];
                $jumpUrl = Yii::app()->createUrl('manager/keyWords', array('type' => ImagetextreplayModel::IMAGE_TEXT_REPLAY_TYPE));
            } elseif (isset($_POST['TextreplayModel'])) {
                $model->attributes = $_POST['TextreplayModel'];
                $keywords = $_POST['TextreplayModel']['keywords'];
                $isAccurate = $_POST['TextreplayModel']['isAccurate'];
                $jumpUrl = Yii::app()->createUrl('manager/keyWords');
            }
            $model->wechatId = $this->wechatInfo->id;
            $model->type = SubscribereplayModel::KEYWORDS_TYPE;
            $keywordsArray = explode(',', $keywords);
            if ($model->validate()) {
                $model->save();
                foreach ($keywordsArray as $k) {
                    //新加关键词
                    $keywordsModel = new KeywordsModel();
                    $keywordsModel->responseId = $model->id;
                    $keywordsModel->name = $k;
                    $keywordsModel->isAccurate = $isAccurate;
                    $keywordsModel->wechatId = $this->wechatInfo->id;
                    $keywordsModel->type = $type;
                    $keywordsModel->save();
                }
                ShowMessage::success('添加成功', $jumpUrl);
            }
        }

        $view = 'keywordsReplayCreate';
        $this->render($view, array('model' => $model, 'type' => $type));
    }

    public function actionKeyWordsUpdate($id)
    {
        $type = Yii::app()->request->getParam('type');
        $oldKeywords = array();
        $oldIsAccurate = 0;
        switch ($type) {
            case ImagetextreplayModel::IMAGE_TEXT_REPLAY_TYPE:
                $model = ImagetextreplayModel::model()->with('imagetextreplay_keywords')->findByPk($id);
                $comm = '';
                foreach ($model->imagetextreplay_keywords as $keywords) {
                    $oldKeywords[] = $keywords->name;
                    $oldIsAccurate = $keywords->isAccurate;
                    $model->keywords .= $comm . $keywords->name;
                    $comm = ',';
                    $model->isAccurate = $keywords->isAccurate;
                }
                break;
            case TextreplayModel::TEXT_REPLAY_TYPE:
                $model = TextreplayModel::model()->with('textreplay_keywords')->findByPk($id);
                $comm = '';
                foreach ($model->textreplay_keywords as $keywords) {
                    $oldKeywords[] = $keywords->name;
                    $oldIsAccurate = $keywords->isAccurate;
                    $model->keywords .= $comm . $keywords->name;
                    $model->isAccurate = $keywords->isAccurate;
                    $comm = ',';
                }
                break;
        }
        if (isset($_POST['ImagetextreplayModel']) || isset($_POST['TextreplayModel'])) {
            if (isset($_POST['ImagetextreplayModel'])) {
                $model->attributes = $_POST['ImagetextreplayModel'];
                $keywords = $_POST['ImagetextreplayModel']['keywords'];
                $isAccurate = $_POST['ImagetextreplayModel']['isAccurate'];
                $jumpUrl = Yii::app()->createUrl('manager/keyWords', array('type' => ImagetextreplayModel::IMAGE_TEXT_REPLAY_TYPE));
            } elseif (isset($_POST['TextreplayModel'])) {
                $model->attributes = $_POST['TextreplayModel'];
                $keywords = $_POST['TextreplayModel']['keywords'];
                $isAccurate = $_POST['TextreplayModel']['isAccurate'];
                $jumpUrl = Yii::app()->createUrl('manager/keyWords');
            }
            $keywordsArray = explode(',', $keywords);

            $keywordsAdd = array_unique(array_merge($oldKeywords, $keywordsArray));
            $arrayDel = array_diff($keywordsAdd, $keywordsArray); //删除了的关键字
            $arrayAdd = array_diff($keywordsAdd, $oldKeywords); //添加的关键字
            if ($model->validate()) {
                foreach ($arrayAdd as $k) {
                    //新加关键词
                    $keywordsModel = new KeywordsModel();
                    $keywordsModel->responseId = $id;
                    $keywordsModel->name = $k;
                    $keywordsModel->isAccurate = $isAccurate;
                    $keywordsModel->wechatId = $this->wechatInfo->id;
                    $keywordsModel->type = $type;
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
                $model->save();
                ShowMessage::success('编辑成功', $jumpUrl);
            }
        }

        $view = 'keywordsReplayCreate';
        $this->render($view, array('model' => $model, 'type' => $type));
    }

    public function actionKeyWordsDelete($id)
    {
        $type = Yii::app()->request->getParam('type');
        switch ($type) {
            case ImagetextreplayModel::IMAGE_TEXT_REPLAY_TYPE:
                $model = ImagetextreplayModel::model()->findByPk($id);
                $jumpUrl = Yii::app()->createUrl('manager/keyWords', array('type' => ImagetextreplayModel::IMAGE_TEXT_REPLAY_TYPE));
                break;
            case TextreplayModel::TEXT_REPLAY_TYPE:
                $model = TextreplayModel::model()->findByPk($id);
                $jumpUrl = Yii::app()->createUrl('manager/keyWords');
                break;
        }
        $model->delete();
        //删除相关关键词
        KeywordsModel::model()->deleteAll('responseId=:responseId', array(':responseId' => $id));
        ShowMessage::success('删除成功', $jumpUrl);
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