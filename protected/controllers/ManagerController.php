<?php

class ManagerController extends WechatManagerController
{

    public function __construct($id){
        parent::__construct($id);
    }

    public function actionIndex($id)
    {
        $wechatInfo = $this->_getWechatInfo($id);
        $this->render('index');
    }

    public function actionAutoReplay($id)
    {
        $wechatInfo = $this->_getWechatInfo($id);
        $this->render('autoReplay');
    }

    public function actionKeyWords($id)
    {
        $wechatInfo = $this->_getWechatInfo($id);
        $this->render('keyWords');
    }

    public function actionTest()
    {

    }
    protected function _getWechatInfo($id){
        $wechatInfo = WechatModel::model()->findByPk($id);
        if(!$wechatInfo){
            ShowMessage::error('公众帐号不存在!');
        }
        return $wechatInfo;
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