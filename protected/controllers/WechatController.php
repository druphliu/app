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
        //限制  只准创建一个用户
        $count = WechatModel::model()->count('uid=:uid',array(':uid'=>Yii::app()->session['userInfo']['uid']));
        if($count>=1)
            $this->showError('你已创建账户,请勿重复创建');
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
                $this->showSuccess('添加成功！', Yii::app()->createUrl('wechat/index'));
            }
        }
        Yii::app()->clientScript->scriptMap['jquery.js'] = false;
        $this->render('add', array('model' => $model));
    }

    public function actionUpdate($id)
    {
        $model = WechatModel::model()->findByPk($id);
        if ($model->uid != Yii::app()->session['userInfo']['uid']) {
            return;
        }

        if (isset($_POST['WechatModel'])) {
            unset($_POST['WechatModel']['type']);
            unset($_POST['WechatModel']['originalId']);
            $model->attributes = $_POST['WechatModel'];
            $model->apiUrl = Yii::app()->params['siteUrl'] . Yii::app()->createUrl('/api/index/id/' . $model->originalId);
            if ($model->validate()) {
                $model->save();
                $this->showSuccess('修改成功！', Yii::app()->createUrl('wechat/index'));
            }
        }
        Yii::app()->clientScript->scriptMap['jquery.js'] = false;
        $this->render('update', array('model' => $model));
    }

    public function actionDelete($id)
    {
        $model = WechatModel::model()->find('id=:id and uid=:uid',array(':id'=>$id,':uid'=>Yii::app()->session['userInfo']['uid']));
        $model->delete();

        $this->showSuccess('删除成功！', Yii::app()->createUrl('wechat/index'));
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