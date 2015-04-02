<?php

class UserController extends Controller
{
	public function actionIndex()
	{
        $dataProvider = new CActiveDataProvider('AdminUserModel', array(
            'criteria' => array(
                'order' => 'uid desc',
            ),
            //'pagination' => false,
            'pagination' => array(
                'pageSize' => Page::SIZE,
            ),
        ));
        $this->render('index', array('data' => $dataProvider->getData(),'pages'=>$dataProvider->getPagination()));
	}

    public function actionProfile(){
        $model = AdminUserModel::model()->find(array('condition'=>'username=:username','params'=>array(':username'=>Yii::app()->user->id)));
        if (isset($_POST['AdminUserModel'])) {
            $model->attributes = $_POST['AdminUserModel'];
            if ($model->validate()) {
                $model->save();
                $this->redirect(array('group/user'));
            }
        }
        $this->render('/user/profile',array('model'=>$model));
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