<?php

/**
 * Created by app.
 * User: druphliu
 * Date: 14-10-21
 * Time: 下午1:34
 */
class OpenController extends WechatManagerController
{
    public function actionIndex()
    {
        $this->layout = '/layouts/memberList';
        $dataProvider = new CActiveDataProvider('OpenPlatformModel', array(
            'criteria' => array(
                'order' => 't.id DESC',
                'condition' => 'wechatId=' . $this->wechatInfo->id,
            ),
            //'pagination' => false,
            'pagination' => array(
                'pageSize' => Page::SIZE,
                'pageVar' => 'page'
            ),
        ));;
        $this->render('index', array('data' => $dataProvider->getData(), 'pages' => $dataProvider->getPagination()));
    }

    public function actionAdd()
    {
        $model = new OpenPlatformModel();
        if (isset($_POST['OpenPlatformModel'])) {
            $dateTime = time();
            $model->attributes = $_POST['OpenPlatformModel'];
            $model->wechatId = $this->wechatInfo->id;
            $model->created_at = $dateTime;
            if ($model->validate()) {
                $model->save();
                ShowMessage::success('添加成功！', Yii::app()->createUrl('open/index'));
            }
        }
        $this->render('create', array('model' => $model));
    }

    public function actionUpdate($id)
    {
        $model = OpenPlatformModel::model()->findByPk($id);
        if ($model->wechatId != $this->wechatInfo->id)
            ShowMessage::error('数据不存在');
        if (isset($_POST['OpenPlatformModel'])) {
            $model->attributes = $_POST['OpenPlatformModel'];
            $model->status = 0;
            if ($model->validate()) {
                $model->save();
                ShowMessage::success('编辑成功！', Yii::app()->createUrl('open/index'));
            }
        }
        $this->render('update', array('model' => $model));
    }

    public function actionDelete($id)
    {
        $model = OpenPlatformModel::model()->findByPk($id);
        if ($model->wechatId != $this->wechatInfo->id)
            return;
        //检查是否已接入关键字
        $openReplayInfo = OpenReplayModel::model()->find('openId=:openId', array(':openId' => $model->id));
        if ($openReplayInfo)
            ShowMessage::error('请先删除转接回复');
        $model->delete();
        ShowMessage::success('删除成功', Yii::app()->createUlr('open/idex'));
    }

    public function actionReplay()
    {
        $this->layout = '/layouts/memberList';
        $with = array('open_keywords', 'open_openPlatForm');
        $whereType = " and open_keywords.type='" . OpenReplayModel::OPEN_TYPE . "'";
        $dataProvider = new CActiveDataProvider('OpenReplayModel', array(
            'criteria' => array(
                'order' => 't.id DESC',
                'with' => $with,
                'condition' => "t.wechatId = {$this->wechatInfo->id} $whereType",
                'together' => true
            ),
            //'pagination' => false,
            'pagination' => array(
                'pageSize' => Page::SIZE,
                'pageVar' => 'page'
            ),
        ));
        $this->render('replay', array('data' => $dataProvider->getData(), 'pages' => $dataProvider->getPagination(),
            'wechatInfo' => $this->wechatInfo));
    }

    public function actionReplayAdd()
    {
        $menuList = array();
        $open = array('' => '请选择') + CHtml::listData(OpenPlatformModel::model()->findAll('wechatId=:wechatId and status=:status', array(':wechatId' => $this->wechatInfo->id, ':status' => 1)), 'id', 'name');

        $model = new OpenReplayModel();
        if (isset($_POST['OpenReplayModel'])) {
            $model->attributes = $_POST['OpenReplayModel'];
            $model->wechatId = $this->wechatInfo->id;
            if ($model->validate()) {
                $model->save();
                $keywords = $_POST['OpenReplayModel']['keywords'];
                $isAccurate = $_POST['OpenReplayModel']['isAccurate'];
                $keywordsArray = explode(',', $keywords);
                foreach ($keywordsArray as $k) {
                    $keywordsModel = new KeywordsModel();
                    $keywordsModel->responseId = $model->id;
                    $keywordsModel->type = OpenReplayModel::OPEN_TYPE;
                    $keywordsModel->isAccurate = $isAccurate;
                    $keywordsModel->name = $k;
                    $keywordsModel->wechatId = $this->wechatInfo->id;
                    $keywordsModel->save();
                }
                ShowMessage::success('添加成功', Yii::app()->createUrl('open/replay'));
            }
        }
        Yii::app()->clientScript->scriptMap['jquery.js'] = false;
        $this->render('replayCreate', array('model' => $model, 'wechatId' => $this->wechatInfo->id,
            'responseId' => 0, 'open' => $open, 'menuList' => $menuList));
    }

    public function actionReplayUpdate($id)
    {
        $menuList = array();
        $keyword = $common = '';
        $model = OpenReplayModel::model()->findByPk($id);
        if ($model->wechatId != $this->wechatInfo->id)
            return;
        $open = array('' => '请选择') + CHtml::listData(OpenPlatformModel::model()->findAll('wechatId=:wechatId and status=:status', array(':wechatId' => $this->wechatInfo->id, ':status' => 1)), 'id', 'name');
        $keywords = KeywordsModel::model()->findAll('type=:type and responseId=:responseId',
            array(':type' => OpenReplayModel::OPEN_TYPE, ':responseId' => $id));
        foreach ($keywords as $k) {
            $oldKeywords[] = $k->name;
            $oldIsAccurate = $k->isAccurate;
            $isAccurate = $k->isAccurate;
            $keyword .= $common . $k->name;
            $common = ',';
        }
        $model->keywords = $keyword;
        $model->isAccurate = $isAccurate;
        if (isset($_POST['OpenReplayModel'])) {
            $model->attributes = $_POST['OpenReplayModel'];
            if ($model->validate()) {
                //根据活动类型更新不同关联表
                $keywordsArray = explode(',', $_POST['OpenReplayModel']['keywords']);
                $keywordsAdd = array_unique(array_merge($oldKeywords, $keywordsArray));
                $arrayDel = array_diff($keywordsAdd, $keywordsArray); //删除了的关键字
                $arrayAdd = array_diff($keywordsAdd, $oldKeywords); //添加的关键字
                $arrayAlive = array_diff($oldKeywords, $arrayAdd); //没改变的
                $newIsAccurate = $_POST['OpenReplayModel']['isAccurate'];
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
                    $keywordsModel->type = OpenReplayModel::OPEN_TYPE;
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
                ShowMessage::success('编辑成功', Yii::app()->createUrl('open/replay'));
            }
        }
        Yii::app()->clientScript->scriptMap['jquery.js'] = false;
        $this->render('replayUpdate', array('model' => $model, 'wechatId' => $this->wechatInfo->id, 'responseId' => $id, 'open' => $open, 'menuList' => $menuList));
    }

    public function actionReplayDelete($id)
    {
        $model = OpenReplayModel::model()->findByPk($id);
        //删除关键字
        KeywordsModel::model()->deleteAll('responseId=:responseId and type=:type', array(':responseId' => $id, ':type' => OpenReplayModel::OPEN_TYPE));
        $model->delete();
        ShowMessage::success('删除成功', Yii::app()->createUrl('open/replay'));
    }
}