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
//                print_r($this);
            }
        }
        $menuList = AdminMenu::$menuList;
        foreach($menuList as $k=>$m){
            $mainMenu = array($m['act']=>$k);
            foreach($m['action'] as $act){
                $subMain['action'][] = array($act['act']=>$act['name']);
            }
            $action[]=array_merge($mainMenu,$subMain);
        }
		$this->render('create',array('model'=>$model,'action'=>$action));
	}

	public function actionIndex()
	{
        $data = GroupModel::model()->findAll();
		$this->render('index',array('data'=>$data));
	}

	public function actionUpdate()
	{
		$this->render('update');
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