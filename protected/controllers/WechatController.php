<?php

class WechatController extends MemberController
{
	public function actionIndex()
	{
        $this->layout = '//layouts/memberList';
        $dataProvider = new CActiveDataProvider('WechatModel', array(
            'criteria' => array(
                'order' => 'created_at desc',
            ),
            //'pagination' => false,
            'pagination' => array(
                'pageSize' => Page::SIZE,
            ),
        ));
        $this->render('index', array('data' => $dataProvider->getData(),'pages'=>$dataProvider->getPagination()));
	}

    public function actionAdd(){
        $model = new WechatModel();
        if (isset($_POST['WechatModel'])) {
            $dateTime = time();
            $model->attributes = $_POST['WechatModel'];
            $model->uid =  Yii::app()->session['userInfo']['uid'];
            $model->created_at = $dateTime;
            $model->updated_at = $dateTime;
            if ($model->validate()) {
                $model->save();
                ShowMessage::success('添加成功！',Yii::app()->createUrl('wechat/index'));
            }
        }
        $this->render('add', array('model' => $model));
    }

    public function actionUpdate($id)
    {
        $model = WechatModel::model()->findByPk($id);
        if (isset($_POST['WechatModel'])) {
            $model->attributes = $_POST['WechatModel'];
            if ($model->validate()) {
                $model->save();
                ShowMessage::success('修改成功！',Yii::app()->createUrl('wechat/index'));
            }
        }
        $this->render('update', array('model' => $model));
    }

    public function actionDelete($id)
    {
        $model = WechatModel::model()->findByPk($id);
        $model->delete();

        ShowMessage::success('删除成功！',Yii::app()->createUrl('wechat/index'));
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