<?php

class ManagerController extends WechatManagerController
{
    public function actionIndex()
    {
        $this->render('index');
    }

    public function actionSubscribeReplay()
    {
        $validate = true;
        $msg = '';
        $imageTextList = $dataList = array();
        $type = Yii::app()->request->getParam('type');
        $subscribeInfo = SubscribereplayModel::model()->find('wechatId=:wechatId', array(":wechatId" => $this->wechatInfo->id));
        if ($type) {
            switch ($type) {
                case ImagetextreplayModel::IMAGE_TEXT_REPLAY_TYPE:
                    $model = ImagetextreplayModel::model()->find('wechatId=:wechatId and type=:type', array(':wechatId' => $this->wechatInfo->id, ":type" => SubscribereplayModel::SUBSCRIBE_TYPE));
                    if ($model) {
                        $imageTextList = ImagetextreplayModel::model()->findAll('parentId=:parentId', array(':parentId' => $model->id));
                        $dataList = CHtml::listData($imageTextList, 'id', 'id');
                    } else {
                        $model = new ImagetextreplayModel();
                    }
                    break;
                case TextReplayModel::TEXT_REPLAY_TYPE:
                    $model = TextReplayModel::model()->find('wechatId=:wechatId and type=:type', array(':wechatId' => $this->wechatInfo->id, ":type" => SubscribereplayModel::SUBSCRIBE_TYPE));
                    $model = $model ? $model : new TextReplayModel();
                    break;
            }
        } else {
            $type = TextReplayModel::TEXT_REPLAY_TYPE;
            if ($subscribeInfo) {
                switch ($subscribeInfo->type) {
                    case TextReplayModel::TEXT_REPLAY_TYPE:
                        $model = TextReplayModel::model()->findByPk($subscribeInfo->responseId);
                        break;
                    case ImagetextreplayModel::IMAGE_TEXT_REPLAY_TYPE:
                        $model = ImagetextreplayModel::model()->findByPk($subscribeInfo->responseId);
                        $imageTextList = ImagetextreplayModel::model()->findAll('parentId=:parentId', array(':parentId' => $model->id));
                        $dataList = CHtml::listData($imageTextList, 'id', 'id');
                        $type = ImagetextreplayModel::IMAGE_TEXT_REPLAY_TYPE;
                        break;
                }
            } else {
                $model = new TextReplayModel();
            }
        }
        if (isset($_POST['TextReplayModel']) || isset($_POST['count'])) {
            if ($type == TextReplayModel::TEXT_REPLAY_TYPE) {
                $model->attributes = $_POST['TextReplayModel'];
            } else {
                $model->title = $_POST['title1'];
                $model->imgUrl = $_POST['src1'];
                $model->description = $_POST['summary1'];
                $model->url = $_POST['url1'];
                $count = $_POST['count'];
                for ($i = 2; $i <= $count; $i++) {
                    $title = $_POST['title' . $i];
                    $summary = $_POST['summary' . $i];
                    $imgUrl = $_POST['src' . $i];
                    $url = $_POST['url' . $i];
                    $id = isset($_POST['id' . $i]) ? $_POST['id' . $i] : 0;
                    $formName = 'form' . $i;
                    if ($id) {
                        $$formName = ImagetextreplayModel::model()->findByPk($id);
                        if (in_array($id, $dataList))
                            unset($dataList[$id]);
                    }
                    $$formName = isset($$formName) ? $$formName : new ImagetextreplayModel();
                    $$formName->wechatId = $this->wechatInfo->id;
                    $$formName->type = Globals::TYPE_SUBSCRIBE;
                    $$formName->title = $title;
                    $$formName->description = $summary;
                    $$formName->imgUrl = $imgUrl;
                    $$formName->url = $url;
                    if (!$$formName->validate()) {
                        $validate = false;
                        foreach ($$formName->getErrors() as $e) {
                            $msg .= $e[0];
                        }
                    };
                }
            }
            $model->type = SubscribereplayModel::SUBSCRIBE_TYPE;
            $model->wechatId = $this->wechatInfo->id;
            if ($model->validate() && $validate) {
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
                if (isset($count)) {
                    for ($i = 2; $i <= $count; $i++) {
                        $formName = 'form' . $i;
                        $$formName->parentId = $model->id;
                        $$formName->save();
                    }
                    echo json_encode(array('status'=>1,'msg'=>$msg));
                    return;
                }else{
                    ShowMessage::success('添加成功！');
                }
            }else{
                echo json_encode(array('status'=>-1,'msg'=>$msg));
                return;
            }
        }
        $this->render('subscribeReplay', array('model' => $model, 'type' => $type, 'imageTextList' => $imageTextList));
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
                            SubscribereplayModel::KEYWORDS_TYPE . "' and textreplay_keywords.type='" . TextReplayModel::TEXT_REPLAY_TYPE . "'",
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
                        'condition' => "t.wechatId = {$this->wechatInfo->id} and t.type='" . SubscribereplayModel::KEYWORDS_TYPE .
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
        $validate = true;
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
            if (isset($_POST['ImagetextreplayModel'])) {
                $count = $_POST['count'];
                $model->title = $_POST['title1'];
                $model->description = $_POST['summary1'];
                $model->imgUrl = $_POST['src1'];
                $model->url = $_POST['url1'];
                $keywords = $_POST['ImagetextreplayModel']['keywords'];
                $isAccurate = $_POST['ImagetextreplayModel']['isAccurate'];
                if ($count > 1) {
                    for ($i = 2; $i <= $count; $i++) {
                        ${'model' . $i} = new ImagetextreplayModel();
                        ${'model' . $i}->title = $_POST['title' . $i];
                        ${'model' . $i}->description = $_POST['summary' . $i];
                        ${'model' . $i}->type = Globals::TYPE_KEYWORDS;
                        ${'model' . $i}->imgUrl = $_POST['src' . $i];
                        ${'model' . $i}->url = $_POST['url' . $i];
                        ${'model' . $i}->wechatId = $this->wechatInfo->id;
                        $validate &= ${'model' . $i}->validate();
                    }
                }
                $jumpUrl = Yii::app()->createUrl('manager/keyWords');
            } elseif (isset($_POST['TextReplayModel'])) {
                $model->attributes = $_POST['TextReplayModel'];
                $keywords = $_POST['TextReplayModel']['keywords'];
                $isAccurate = $_POST['TextReplayModel']['isAccurate'];
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
                //处理列表
                if (isset($_POST['ImagetextreplayModel'])) {
                    for ($i = 2; $i <= $count; $i++) {
                        ${'model' . $i}->parentId = $model->id;
                        ${'model' . $i}->save();
                    }
                    echo json_encode(array('status' => 1, 'url' => $jumpUrl));
                    return;
                }
                ShowMessage::success('添加成功', $jumpUrl);
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
        $focusId = $id;
        $type = Yii::app()->request->getParam('type');
        $oldKeywords = $imageTextList = array();
        $oldIsAccurate = 0;
        $validate = true;
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
                    $model->isAccurate = $keywords->isAccurate;
                }
                $imageTextList = ImagetextreplayModel::model()->findAll('parentId=:parentId', array(':parentId' => $id));
                $listData = CHtml::listData($imageTextList, 'id', 'id');
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
                $keywords = $_POST['ImagetextreplayModel']['keywords'];
                $isAccurate = $_POST['ImagetextreplayModel']['isAccurate'];
                $jumpUrl = Yii::app()->createUrl('manager/keyWords', array('type' => ImagetextreplayModel::IMAGE_TEXT_REPLAY_TYPE));
                if ($count > 1) {
                    for ($i = 2; $i <= $count; $i++) {
                        $id = $_POST['id' . $i] ? $_POST['id' . $i] : 0;
                        if ($id) {
                            ${'model' . $i} = ImagetextreplayModel::model()->findByPk($id);
                            if (in_array($id, $listData))
                                unset($listData[$id]);
                        }
                        ${'model' . $i} = isset(${'model' . $id}) ? ${'model' . $id} : new ImagetextreplayModel();
                        ${'model' . $i}->title = $_POST['title' . $i];
                        ${'model' . $i}->description = $_POST['summary' . $i];
                        ${'model' . $i}->type = Globals::TYPE_KEYWORDS;
                        ${'model' . $i}->imgUrl = $_POST['src' . $i];
                        ${'model' . $i}->url = $_POST['url' . $i];
                        ${'model' . $i}->wechatId = $this->wechatInfo->id;
                        ${'model' . $i}->parentId = $model->id;
                        $validate &= ${'model' . $i}->validate();
                    }
                }
            } elseif (isset($_POST['TextReplayModel'])) {
                $model->attributes = $_POST['TextReplayModel'];
                $keywords = $_POST['TextReplayModel']['keywords'];
                $isAccurate = $_POST['TextReplayModel']['isAccurate'];
                $jumpUrl = Yii::app()->createUrl('manager/keyWords');
            }
            $keywordsArray = explode(',', $keywords);
            $keywordsAdd = array_unique(array_merge($oldKeywords, $keywordsArray));
            $arrayDel = array_diff($keywordsAdd, $keywordsArray); //删除了的关键字
            $arrayAdd = array_diff($keywordsAdd, $oldKeywords); //添加的关键字
            $arrayAlive = array_diff($oldKeywords, $arrayAdd); //没改变的
            if ($model->validate() && $validate) {
                if (($isAccurate != $oldIsAccurate) && $arrayAlive) {
                    //是否精准匹配改变了
                    foreach ($arrayAlive as $name) {
                        $keywordsModel = KeywordsModel::model()->find('name=:name', array(':name' => $name));
                        $keywordsModel->isAccurate = $isAccurate;
                        $keywordsModel->save();
                    }
                }
                foreach ($arrayAdd as $k) {
                    //新加关键词
                    $keywordsModel = new KeywordsModel();
                    $keywordsModel->responseId = $focusId;
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
                if (isset($_POST['ImagetextreplayModel'])) {
                    for ($i = 2; $i <= $count; $i++) {
                        ${'model' . $i}->save();
                    }
                    if ($listData) {
                        foreach ($listData as $id) {
                            ImagetextreplayModel::model()->deleteByPk($id);
                        }
                    }
                    echo json_encode(array('status' => 1, 'url' => $jumpUrl));
                    return;
                } else {
                    ShowMessage::success('编辑成功', $jumpUrl);
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