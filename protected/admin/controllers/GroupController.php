<?php

class GroupController extends Controller
{
	public function actionCreate()
	{
        $model = new GroupModel();
        if(isset($_POST['GroupModel']))
        {
            // 收集用户输入的数据
            $model->attributes=$_POST['GroupModel'];
            if($model->validate()){
				$model->save();
				$this->redirect(array('index'));
            }
        }
		$this->render('create',array('model'=>$model));
	}

	public function actionIndex()
	{
        $this->layout = '//layouts/list';
        $data = GroupModel::model()->findAll();
		$this->render('index',array('data'=>$data));
	}

	public function actionUpdate($id)
	{
		$model = GroupModel::model()->findByPk($id);
		if(isset($_POST['GroupModel']))
        {
			$model->attributes=$_POST['GroupModel'];
			if($model->validate()){
					$model->save();
					$this->redirect(array('index'));
				}
		}
		$this->render('update',array('model'=>$model));
	}
	
	public function actionDelete($id)
	{
		$model = GroupModel::model()->findByPk($id);
		$model->delete();
		$this->redirect(array('index'));
		
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