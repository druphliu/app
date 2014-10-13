<?php

class WechatController extends MemberController
{
    public function actionIndex()
    {
        $this->layout = '//layouts/memberList';
        $dataProvider = new CActiveDataProvider('WechatModel', array(
            'criteria' => array(
                'order' => 'created_at desc',
                'condition' => 'uid=' . Yii::app()->session['userInfo']['uid'],
            ),
            //'pagination' => false,
            'pagination' => array(
                'pageSize' => Page::SIZE,
            ),
        ));
        $this->render('index', array('data' => $dataProvider->getData(), 'pages' => $dataProvider->getPagination()));
    }

    public function actionAdd()
    {
        $model = new WechatModel();
        if (isset($_POST['WechatModel'])) {
            $dateTime = time();
            $model->attributes = $_POST['WechatModel'];
            $model->uid = Yii::app()->session['userInfo']['uid'];
            $model->created_at = $dateTime;
            $model->updated_at = $dateTime;
            $model->token = md5($model->originalId);
            $model->apiUrl = Yii::app()->params['siteUrl'] . Yii::app()->createUrl('/api/index/id/' . $model->originalId);
            if ($model->validate()) {
                $model->save();
                ShowMessage::success('添加成功！', Yii::app()->createUrl('wechat/index'));
            }
        }
        $this->render('add', array('model' => $model));
    }

    public function actionUpdate($id)
    {
        $model = WechatModel::model()->findByPk($id);
        if ($model->uid != Yii::app()->session['userInfo']['uid']) {
            return;
        }
        if (isset($_POST['WechatModel'])) {
            $model->attributes = $_POST['WechatModel'];
            if ($model->validate()) {
                $model->save();
                ShowMessage::success('修改成功！', Yii::app()->createUrl('wechat/index'));
            }
        }
        $this->render('update', array('model' => $model));
    }

    public function actionDelete($id)
    {
        $model = WechatModel::model()->find('id=:id and uid=:uid',array(':id'=>$id,':uid'=>Yii::app()->session['userInfo']['uid']));
        $model->delete();

        ShowMessage::success('删除成功！', Yii::app()->createUrl('wechat/index'));
    }

    public function actionApi($id)
    {
        $wechatInfo = WechatModel::model()->findByPk($id);
        echo json_encode(array('token' => $wechatInfo->token, 'apiUrl' => $wechatInfo->apiUrl));
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