<?php

class UserController extends MemberController
{
	public function actionInfo()
	{
        $userInfo = Yii::app()->session['userInfo'];
        $model = UserModel::model()->find(array('condition'=>'uid=:uid','params'=>array(':uid'=>$userInfo['uid'])));
        if (isset($_POST['UserModel'])) {
            $model->attributes = $_POST['UserModel'];
            if ($model->validate()) {
                $model->save();
                ShowMessage::success('修改成功！',Yii::app()->createUrl('user/info'));
            }
        }
		$this->render('info',array('model'=>$model));
	}
    public function actionPswd(){
        $userInfo = Yii::app()->session['userInfo'];
        $model = UserModel::model()->find(array('condition'=>'uid=:uid','params'=>array(':uid'=>$userInfo['uid'])));
        $model->scenario = 'pswd';
        if (isset($_POST['UserModel'])) {
            $model->attributes = $_POST['UserModel'];
            if ($model->validate()) {
                $model->pswd = md5($_POST['UserModel']['newpswd']);
                $model->save();
                ShowMessage::success('修改成功！',Yii::app()->createUrl('user/pswd'));
            }
        }
        $this->render('pswd', array('model' => $model));
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