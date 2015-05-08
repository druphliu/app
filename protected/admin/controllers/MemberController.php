<?php

class MemberController extends Controller
{
    public function actionCreate()
    {
        $model = new MemberModel();
        if (isset($_POST['MemberModel'])) {
            // 收集用户输入的数据
            $model->attributes = $_POST['MemberModel'];
            $model->scenario = 'pswd';
            $model->created_at = time();
            if ($model->validate()) {
                $model->pswd = md5($_POST['MemberModel']['newpswd']);
                $model->save();
                $this->redirect(array('index'));
            }
        }
        $this->render('create', array('model' => $model));
    }

    public function actionIndex()
    {
        $this->layout = '//layouts/list';
        $dataProvider = new CActiveDataProvider('MemberModel', array(
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

    public function actionUpdate($id)
    {
        $model = MemberModel::model()->findByPk($id);
        if (isset($_POST['MemberModel'])) {
            $model->attributes = $_POST['MemberModel'];
            $model->scenario = 'pswd';
            $model->updated_at = time();
            if ($model->validate()) {
                if(isset($_POST['MemberModel']['newpswd']) && $_POST['MemberModel']['newpswd'])
                    $model->pswd = md5($_POST['MemberModel']['newpswd']);
                $model->save();
                $this->redirect(array('index'));
            }
        }
        $this->render('update', array('model' => $model));
    }

    public function actionDelete($id)
    {
        $model = MemberModel::model()->findByPk($id);
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