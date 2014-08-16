<?php

class VipController extends MemberController
{
    public function actionIndex(){

    }

	public function actionLv()
	{
        $model = new VipPaymentModel();
        $_priceList = PriceList::returnPriceList();
        foreach($_priceList as $l=>$p){
            $priceList[$p] = $l;
        }
        for($i=1;$i<=12;$i++){
            $mouth[$i] = $i.'个月';
        }
        if (isset($_POST['VipPaymentModel'])) {
            $dateTime = time();
            $model->attributes = $_POST['VipPaymentModel'];
            $model->uid =  Yii::app()->session['userInfo']['uid'];
            $model->username = Yii::app()->session['userInfo']['username'];
            $model->created_at = $dateTime;
            $model->orderId = $this->_createOrderSns();
            if ($model->validate()) {
                //校验价格
                $price = $model->payCode;
                $count = $model->count;
                $amount = $price*$count;
                if($amount!=$model->amount){
                    ShowMessage::error('数据有误!');
                }
                $model->payCode = $priceList[$model->payCode];
                $model->save();
                //ShowMessage::success('添加成功！',Yii::app()->createUrl('vip/index'));
            }
        }
		$this->render('lv',array('model'=>$model,'priceList'=>$priceList,'mouth'=>$mouth));
	}

    /**
     * 创建订单号
     */
    public function _createOrderSns(){
        $unique = number_format(hexdec(uniqid()),0,'','');
        return $unique;
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