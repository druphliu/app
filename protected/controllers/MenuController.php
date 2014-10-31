<?php

class MenuController extends WechatManagerController
{
    public function actionIndex()
    {
        $model = MenuactionModel::model()->find('wechatId=:wechatId',array(":wechatId"=>$this->wechatInfo->id));
        if($_POST){
            print_r(json_decode($_POST['output']));
            print_r($_POST);exit;
        }

        $this->render('index',array('model'=>$model));
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