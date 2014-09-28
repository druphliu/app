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
                            $model = TextreplayModel::model()->findByPk($subscribeInfo->replayId);
                        } else {
                            $textModel = ImagetextreplayModel::model()->findByPk($subscribeInfo->replayId);
                            $model = $textModel ? $textModel : new ImagetextreplayModel();
                        }
                        break;
                    case ImagetextreplayModel::IMAGE_TEXT_REPLAY_TYPE:
                        if ($type == ImagetextreplayModel::IMAGE_TEXT_REPLAY_TYPE) {
                            $model = ImagetextreplayModel::model()->findByPk($subscribeInfo->replayId);
                        } else {
                            $imageTextModel = ImagetextreplayModel::model()->findByPk($subscribeInfo->replayId);
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
                    $subscribeInfo->replayId = $model->id;
                    $subscribeInfo->save();
                } else {
                    $subscribeModel = new SubscribereplayModel();
                    $subscribeModel->replayId = $model->id;
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
                        'condition' => "type='" . SubscribereplayModel::KEYWORDS_TYPE . "'",
                        'order' => 'id DESC',
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
                        'condition' => "type='" . SubscribereplayModel::KEYWORDS_TYPE . "'",
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
                $jumpUrl = Yii::app()->createUrl('manager/keyWords', array('type' => ImagetextreplayModel::IMAGE_TEXT_REPLAY_TYPE));
            } elseif (isset($_POST['TextreplayModel'])) {
                $model->attributes = $_POST['TextreplayModel'];
                $jumpUrl = Yii::app()->createUrl('manager/keyWords');
            }
            $model->wechatId = $this->wechatInfo->id;
            $model->type = SubscribereplayModel::KEYWORDS_TYPE;
            if ($model->validate()) {
                $model->save();
                ShowMessage::success('添加成功', $jumpUrl);
            }
        }

        $view = 'keywordsReplayCreate';
        $this->render($view, array('model' => $model, 'type' => $type));
    }

    public function actionKeyWordsUpdate($id)
    {
        $type = Yii::app()->request->getParam('type');
        switch ($type) {
            case ImagetextreplayModel::IMAGE_TEXT_REPLAY_TYPE:
                $model = ImagetextreplayModel::model()->findByPk($id);
                break;
            case TextreplayModel::TEXT_REPLAY_TYPE:
                $model = TextreplayModel::model()->findByPk($id);
                break;
        }
        if (isset($_POST['ImagetextreplayModel']) || isset($_POST['TextreplayModel'])) {
            if (isset($_POST['ImagetextreplayModel'])) {
                $model->attributes = $_POST['ImagetextreplayModel'];
                $jumpUrl = Yii::app()->createUrl('manager/keyWords', array('type' => ImagetextreplayModel::IMAGE_TEXT_REPLAY_TYPE));
            } elseif (isset($_POST['TextreplayModel'])) {
                $model->attributes = $_POST['TextreplayModel'];
                $jumpUrl = Yii::app()->createUrl('manager/keyWords');
            }
            if ($model->validate()) {
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