<?php

class ManagerController extends WechatManagerController
{
    public function actionIndex()
    {
        $this->render('index');
    }

    public function actionSubscribeReplay()
    {
        $msg = '';
        $imageTextList = $dataList = array();
        $type = Yii::app()->request->getParam('type');
        $subscribeInfo = BasereplayModel::model()->find('wechatId=:wechatId and replayType=:replayType',
            array(":wechatId" => $this->wechatInfo->id, ':replayType' => Globals::REPLAY_TYPE_SUBSCRIBE));
        if ($type) {
            switch ($type) {
                case ImagetextreplayModel::IMAGE_TEXT_REPLAY_TYPE:
                    if ($subscribeInfo && $subscribeInfo->type == Globals::TYPE_IMAGE_TEXT) {
                        $model = ImagetextreplayModel::model()->findByPk($subscribeInfo->responseId);
                        $imageTextList = ImagetextreplayModel::model()->findAll('parentId=:parentId', array(':parentId' => $model->id));
                    } else {
                        $model = new ImagetextreplayModel();
                    }
                    break;
                case TextReplayModel::TEXT_REPLAY_TYPE:
                    if ($subscribeInfo && $subscribeInfo->type == Globals::TYPE_TEXT) {
                        $model = TextReplayModel::model()->findByPk($subscribeInfo->responseId);
                    } else {
                        $model = new TextReplayModel();
                    }
                    break;
            }
        } else {
            $type = Globals::TYPE_TEXT;
            if ($subscribeInfo) {
                if ($subscribeInfo->type == Globals::TYPE_TEXT) {
                    $model = TextReplayModel::model()->findByPk($subscribeInfo->responseId);
                } else {
                    $type = Globals::TYPE_IMAGE_TEXT;
                    $model = ImagetextreplayModel::model()->findByPk($subscribeInfo->responseId);
                    $imageTextList = ImagetextreplayModel::model()->findAll('parentId=:parentId', array(':parentId' => $model->id));
                }
            } else {
                $model = new TextReplayModel();
            }
        }
        if (isset($_POST['TextReplayModel']) || isset($_POST['count'])) {
            if (!$model->wechatId) {
                $model->wechatId = $this->wechatInfo->id;
                $model->type = Globals::TYPE_BASE_REPLAY;
            }
            if ($type == TextReplayModel::TEXT_REPLAY_TYPE) {
                $model->attributes = $_POST['TextReplayModel'];
                $validate = $model->validate();
                $model->save();
            } else {
                $model->title = $_POST['title1'];
                $model->imgUrl = $_POST['src1'];
                $model->description = $_POST['summary1'];
                $model->url = $_POST['url1'];
                $model->save();
                $count = $_POST['count'];
                $validate = $count > 1 ? $this->saveImageText($count, $_POST, $model->id) : $model->validate();
            }
            $model->type = Globals::REPLAY_TYPE_SUBSCRIBE;
            $model->wechatId = $this->wechatInfo->id;
            if ($validate) {
                //如果新加，添加数据到subscribe表反之更新
                if (isset($subscribeInfo)) {
                    $subscribeInfo->type = $type;
                    $subscribeInfo->responseId = $model->id;
                    $subscribeInfo->save();
                } else {
                    $subscribeModel = new BasereplayModel();
                    $subscribeModel->responseId = $model->id;
                    $subscribeModel->type = $type;
                    $subscribeModel->replayType = Globals::REPLAY_TYPE_SUBSCRIBE;
                    $subscribeModel->wechatId = $this->wechatInfo->id;
                    $subscribeModel->save();
                }
                if ($type == Globals::TYPE_IMAGE_TEXT) {
                    die(json_encode(array('status' => 1, 'msg' => $msg)));
                } else {
                    $this->showSuccess('添加成功！');
                }
            } else {
                if ($type == Globals::TYPE_IMAGE_TEXT) {
                    $msg = '编辑失败';
                    die(json_encode(array('status' => -1, 'msg' => $msg)));
                } else {
                    $this->showError('编辑失败');
                }
            }
        }
        $this->render('subscribeReplay', array('model' => $model, 'type' => $type, 'imageTextList' => $imageTextList));
    }

    public function actionDefaultReplay()
    {
        $msg = '';
        $imageTextList = $dataList = array();
        $type = Yii::app()->request->getParam('type');
        $subscribeInfo = BasereplayModel::model()->find('wechatId=:wechatId and replayType=:replayType',
            array(":wechatId" => $this->wechatInfo->id, ':replayType' => Globals::REPLAY_TYPE_DEFAULT));
        if ($type) {
            switch ($type) {
                case ImagetextreplayModel::IMAGE_TEXT_REPLAY_TYPE:
                    if ($subscribeInfo && $subscribeInfo->type == Globals::TYPE_IMAGE_TEXT) {
                        $model = ImagetextreplayModel::model()->findByPk($subscribeInfo->responseId);
                        $imageTextList = ImagetextreplayModel::model()->findAll('parentId=:parentId', array(':parentId' => $model->id));
                    } else {
                        $model = new ImagetextreplayModel();
                    }
                    break;
                case TextReplayModel::TEXT_REPLAY_TYPE:
                    if ($subscribeInfo && $subscribeInfo->type == Globals::TYPE_TEXT) {
                        $model = TextReplayModel::model()->findByPk($subscribeInfo->responseId);
                    } else {
                        $model = new TextReplayModel();
                    }
                    break;
            }
        } else {
            $type = Globals::TYPE_TEXT;
            if ($subscribeInfo) {
                if ($subscribeInfo->type == Globals::TYPE_TEXT) {
                    $model = TextReplayModel::model()->findByPk($subscribeInfo->responseId);
                } else {
                    $type = Globals::TYPE_IMAGE_TEXT;
                    $model = ImagetextreplayModel::model()->findByPk($subscribeInfo->responseId);
                    $imageTextList = ImagetextreplayModel::model()->findAll('parentId=:parentId', array(':parentId' => $model->id));
                }
            } else {
                $model = new TextReplayModel();
            }
        }
        if (isset($_POST['TextReplayModel']) || isset($_POST['count'])) {
            if (!$model->wechatId) {
                $model->wechatId = $this->wechatInfo->id;
                $model->type = Globals::REPLAY_TYPE_DEFAULT;
            }
            if ($type == TextReplayModel::TEXT_REPLAY_TYPE) {
                $model->attributes = $_POST['TextReplayModel'];
                $validate = $model->validate();
                $model->save();
            } else {
                $model->title = $_POST['title1'];
                $model->imgUrl = $_POST['src1'];
                $model->description = $_POST['summary1'];
                $model->url = $_POST['url1'];
                $model->save();
                $count = $_POST['count'];
                $validate = $count > 1 ? $this->saveImageText($count, $_POST, $model->id) : $model->validate();
            }
            $model->type = Globals::REPLAY_TYPE_DEFAULT;
            $model->wechatId = $this->wechatInfo->id;
            if ($validate) {
                //如果新加，添加数据到subscribe表反之更新
                if (isset($subscribeInfo)) {
                    $subscribeInfo->type = $type;
                    $subscribeInfo->responseId = $model->id;
                    $subscribeInfo->save();
                } else {
                    $subscribeModel = new BasereplayModel();
                    $subscribeModel->responseId = $model->id;
                    $subscribeModel->type = $type;
                    $subscribeModel->replayType = Globals::REPLAY_TYPE_DEFAULT;
                    $subscribeModel->wechatId = $this->wechatInfo->id;
                    $subscribeModel->save();
                }
                if ($type == Globals::TYPE_IMAGE_TEXT) {
                    die(json_encode(array('status' => 1, 'msg' => $msg)));
                } else {
                    $this->showSuccess('添加成功！');
                }
            } else {
                if ($type == Globals::TYPE_IMAGE_TEXT) {
                    $msg = '编辑失败';
                    die(json_encode(array('status' => -1, 'msg' => $msg)));
                } else {
                    $this->showError('编辑失败');
                }
            }
        }
        $this->render('defaultReplay', array('model' => $model, 'type' => $type, 'imageTextList' => $imageTextList));
    }

    public function actionKeyWords()
    {
        $this->layout = '/layouts/memberList';
        $type = Yii::app()->request->getParam('type', TextReplayModel::TEXT_REPLAY_TYPE);
        switch ($type) {
            case TextReplayModel::TEXT_REPLAY_TYPE:
                $dataProvider = new CActiveDataProvider('TextReplayModel', array(
                    'criteria' => array(
                        'order' => 't.id DESC',
                        'with' => array('textreplay_keywords'),
                        'condition' => "t.wechatId = {$this->wechatInfo->id} and t.type='" .
                            Globals::TYPE_KEYWORDS . "' and textreplay_keywords.type='" . TextReplayModel::TEXT_REPLAY_TYPE . "'",
                        'together' => true
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
                        'condition' => "t.wechatId = {$this->wechatInfo->id} and t.type='" . Globals::TYPE_KEYWORDS .
                            "' and imagetextreplay_keywords.type='" . ImagetextreplayModel::IMAGE_TEXT_REPLAY_TYPE . "'",
                        'together' => true,
                        'order' => 't.id DESC',
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

        $imageTextList = array();
        $type = Yii::app()->request->getParam('type');
        switch ($type) {
            case ImagetextreplayModel::IMAGE_TEXT_REPLAY_TYPE:
                $model = new ImagetextreplayModel();
                break;
            case TextReplayModel::TEXT_REPLAY_TYPE:
                $model = new TextReplayModel();
                break;
        }
        if (isset($_POST['ImagetextreplayModel']) || isset($_POST['TextReplayModel'])) {
            $model->wechatId = $this->wechatInfo->id;
            $model->type = Globals::TYPE_KEYWORDS;
            if (isset($_POST['ImagetextreplayModel'])) {
                $count = $_POST['count'];
                $model->title = $_POST['title1'];
                $model->description = $_POST['summary1'];
                $model->imgUrl = $_POST['src1'];
                $model->url = $_POST['url1'];
                $model->save();
                $keywords = $_POST['ImagetextreplayModel']['keywords'];
                $isAccurate = $_POST['ImagetextreplayModel']['isAccurate'];
                $validate = $count > 1 ? $this->saveImageText($count, $_POST, $model->id) : $model->validate();
                $jumpUrl = Yii::app()->createUrl('manager/keyWords', array('type' => Globals::TYPE_IMAGE_TEXT));
            } else {
                $model->content = $_POST['content'];
                $keywords = $_POST['keywords'];
                $isAccurate = isset($_POST['isAccurate']) ? $_POST['isAccurate'] : 0;
                $jumpUrl = Yii::app()->createUrl('manager/keyWords');

                $validate = $model->validate();
                $model->save();
            }
            $keywordsArray = explode(',', $keywords);
            if ($validate && $this->saveKeywords($keywordsArray,$model->id,$isAccurate,$type)) {
                if (isset($_POST['ImagetextreplayModel'])) {
                    echo json_encode(array('status' => 1, 'msg' => '添加成功','url'=>$jumpUrl));
                    return;
                }
                $this->showSuccess('添加成功', $jumpUrl);
            } else {
                if (isset($_POST['ImagetextreplayModel'])) {
                    echo json_encode(array('status' => -1, 'msg' => '编辑出错'));
                    return;
                }
            }
        }

        $view = 'keywordsReplayCreate';
        Yii::app()->clientScript->scriptMap['jquery.js'] = false;
        $this->render($view, array('model' => $model, 'type' => $type, 'wechatId' => $this->wechatInfo->id, 'responseId' => 0, 'imageTextList' => $imageTextList));
    }


    public function actionKeyWordsUpdate($id)
    {
        $modelId = $id;
        $type = Yii::app()->request->getParam('type');
        $oldKeywords = $imageTextList = array();
        $oldIsAccurate = 0;
        switch ($type) {
            case ImagetextreplayModel::IMAGE_TEXT_REPLAY_TYPE:
                $model = ImagetextreplayModel::model()->with('imagetextreplay_keywords')->find('t.id=:id and
                imagetextreplay_keywords.type=:type', array(':id' => $id, ':type' => ImagetextreplayModel::IMAGE_TEXT_REPLAY_TYPE));
                $comm = '';
                foreach ($model->imagetextreplay_keywords as $keywords) {
                    $oldKeywords[] = $keywords->name;
                    $oldIsAccurate = $keywords->isAccurate;
                    $model->keywords .= $comm . $keywords->name;
                    $comm = ',';
                    $model->isAccurate &= $keywords->isAccurate;
                }
                break;
            case TextReplayModel::TEXT_REPLAY_TYPE:
                $model = TextReplayModel::model()->with('textreplay_keywords')->find('t.id=:id and
                textreplay_keywords.type=:type', array(':id' => $id, ':type' => TextReplayModel::TEXT_REPLAY_TYPE));
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
        if (isset($_POST['ImagetextreplayModel']) || isset($_POST['TextReplayModel'])) {
            if (isset($_POST['ImagetextreplayModel'])) {
                $count = $_POST['count'];
                $model->title = $_POST['title1'];
                $model->description = $_POST['summary1'];
                $model->imgUrl = $_POST['src1'];
                $model->url = $_POST['url1'];
                $model->save();
                $keywords = $_POST['ImagetextreplayModel']['keywords'];
                $isAccurate = $_POST['ImagetextreplayModel']['isAccurate'];
                $jumpUrl = Yii::app()->createUrl('manager/keyWords', array('type' => ImagetextreplayModel::IMAGE_TEXT_REPLAY_TYPE));
                $validate = $count > 1 ? $this->saveImageText($count, $_POST, $model->id) : $model->validate();
            } else {
                $model->content = $_POST['content'];
                $keywords = $_POST['keywords'];
                $isAccurate = isset($_POST['isAccurate']) ? $_POST['isAccurate'] : 0;
                $jumpUrl = Yii::app()->createUrl('manager/keyWords');
                $validate = $model->validate();
                $model->save();
            }
            $keywordsArray = explode(',', $keywords);
            if ($validate&&$this->saveKeywords($keywordsArray,$modelId,$isAccurate,$type,$oldKeywords,$oldIsAccurate)) {
                if (isset($_POST['ImagetextreplayModel'])) {
                    echo json_encode(array('status' => 1, 'url' => $jumpUrl));
                    return;
                } else {
                    $this->showSuccess('编辑成功', $jumpUrl);
                }
            } else {
                if (isset($_POST['ImagetextreplayModel'])) {
                    echo json_encode(array('status' => -1, 'msg' => '编辑出错'));
                    return;
                }
            }
        }

        $view = 'keywordsReplayCreate';
        Yii::app()->clientScript->scriptMap['jquery.js'] = false;
        $this->render($view, array('model' => $model, 'type' => $type, 'wechatId' => $this->wechatInfo->id, 'responseId' => $id, 'imageTextList' => $imageTextList));
    }

    public function actionKeyWordsDelete($id)
    {
        $type = Yii::app()->request->getParam('type');
        switch ($type) {
            case ImagetextreplayModel::IMAGE_TEXT_REPLAY_TYPE:
                $model = ImagetextreplayModel::model()->findByPk($id);
                $jumpUrl = Yii::app()->createUrl('manager/keyWords', array('type' => ImagetextreplayModel::IMAGE_TEXT_REPLAY_TYPE));
                break;
            case TextReplayModel::TEXT_REPLAY_TYPE:
                $model = TextReplayModel::model()->findByPk($id);
                $jumpUrl = Yii::app()->createUrl('manager/keyWords');
                break;
        }
        $model->delete();
        //删除相关关键词
        KeywordsModel::model()->deleteAll('responseId=:responseId', array(':responseId' => $id));
        $this->showSuccess('删除成功', $jumpUrl);
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