<?php

class RegistrationController extends WechatManagerController
{
    public function actionIndex()
    {
        $with = array('active_keywords');
        $whereType = "and t.type='" . Globals::TYPE_REGISTRATION . "' and active_keywords.type='" . Globals::TYPE_ACTIVE . "'";
        $this->layout = '//layouts/memberList';
        $dataProvider = new CActiveDataProvider('ActiveModel', array(
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
        $this->render('index', array('data' => $dataProvider->getData(), 'pages' => $dataProvider->getPagination(),
            'wechatInfo' => $this->wechatInfo));
    }

    public function actionCreate(){
        $model = new ActiveModel();
        if (isset($_POST['ActiveModel'])) {
            $model->type = Globals::TYPE_REGISTRATION;
            $model->attributes = $_POST['ActiveModel'];
            $model->wechatId = $this->wechatInfo->id;
            //奖项处理
            for ($i = 1; $i <= 3; $i++) {
                ${'award' . $i} = $_POST['award' . $i];
                ${'isentity' . $i} = $_POST['isentity' . $i] ? $_POST['isentity' . $i] : 0;
                $awards[$i] = array('name' => ${'award' . $i}, 'isentity' => ${'isentity' . $i});
            }
            $model->awards = serialize($awards);
            if ($model->validate()) {
                $model->save();
                $keywords = $_POST['ActiveModel']['keywords'];
                $isAccurate = $_POST['ActiveModel']['isAccurate'];
                $keywordsArray = explode(',', $keywords);
                $this->saveKeywords($keywordsArray, $model->id, $isAccurate, Globals::TYPE_ACTIVE);
                ShowMessage::success('添加成功', Yii::app()->createUrl('registration'));
            }
        }
        Yii::app()->clientScript->scriptMap['jquery.js'] = false;
        $this->render('create', array('model' => $model, 'wechatId' => $this->wechatInfo->id, 'responseId' => 0));
    }
}