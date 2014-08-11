<?php

class GroupController extends Controller
{
    public function actionCreate()
    {

        $model = new GroupModel();
        if (isset($_POST['GroupModel'])) {
            // 收集用户输入的数据
            $model->attributes = $_POST['GroupModel'];
            if ($model->validate()) {
                $model->save();
                $this->redirect(array('index'));
            }
        }
        $this->render('create', array('model' => $model));
    }

    public function actionIndex()
    {
        $this->layout = '//layouts/list';
        $dataProvider = new CActiveDataProvider('GroupModel', array(
            'criteria' => array(
                'order' => 'group_id desc',
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
        $model = GroupModel::model()->findByPk($id);
        if (isset($_POST['GroupModel'])) {
            $model->attributes = $_POST['GroupModel'];
            if ($model->validate()) {
                $model->save();
                $this->redirect(array('index'));
            }
        }
        $this->render('update', array('model' => $model));
    }

    public function actionDelete($id)
    {
        $model = GroupModel::model()->findByPk($id);
        if(!$model->isSystem){
            $model->delete();
        }
        $this->redirect(array('index'));

    }

    public function actionView($id){
        $data = GroupModel::model()->findByPk($id);
        $this->render('view',array('data'=>$data));
    }

    public function actionUser()
    {
        $this->layout = '//layouts/list';
        $group = GroupModel::model()->findall();
        foreach ($group as $v) {
            $groupList[$v->group_id] = $v->name;
        }
        $dataProvider = new CActiveDataProvider('UserModel', array(
            'criteria' => array(
                'order' => 'uid desc',
            ),
            //'pagination' => false,
            'pagination' => array(
                'pageSize' => Page::SIZE,
            ),
        ));
        $this->render('userList', array('data' => $dataProvider->getData(), 'pages' => $dataProvider->getPagination(),'group'=>$groupList));
    }

    public function actionUserDelete($id)
    {
        $model = UserModel::model()->findByPk($id);
        $model->delete();
        $this->redirect(array('group/user'));
    }

    public function actionUserCreate()
    {

        $model = new UserModel();
        $group = GroupModel::model()->findall();
        foreach ($group as $v) {
            $groupList[$v->group_id] = $v->name;
        }
        if (isset($_POST['UserModel'])) {
            // 收集用户输入的数据
            $model->attributes = $_POST['UserModel'];
            $model->scenario = 'create';
            if ($model->validate()) {
                //检查用户是否重复
                $model->pswd = md5($_POST['UserModel']['pswd']);
                $model->repswd = md5($_POST['UserModel']['repswd']);
                $model->save();
                $this->redirect(array('group/user'));
            }
        }
        $this->render('userCreate', array('model' => $model, 'group' => $groupList));
    }

    public function actionUserUpdate($id)
    {
        $model = UserModel::model()->findByPk($id);
        $group = GroupModel::model()->findall();
        foreach ($group as $v) {
            $groupList[$v->group_id] = $v->name;
        }
        if (isset($_POST['UserModel'])) {
            $model->attributes = $_POST['UserModel'];
            $model->scenario = 'update';
            if ($model->validate()) {
                if ($_POST['UserModel']['newPswd']) {
                    $model->pswd = md5($_POST['UserModel']['newPswd']);
                }
                $model->save();
                $this->redirect(array('group/user'));
            }
        }
        $this->render('userUpdate', array('model' => $model, 'group' => $groupList));
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