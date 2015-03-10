<?php

class ManagerController extends WechatManagerController
{
    public function actionIndex()
    {
        $with = array('active_keywords');
        $whereType = "and t.type='" . Globals::TYPE_REGISTRATION . "' and active_keywords.type='" . Globals::TYPE_ACTIVE . "'";
        $this->layout = '//layouts/memberList';
        $dataProvider = new CActiveDataProvider('ActiveModel', array(
            'criteria' => array(
                'order' => 't.id DESC',
                'with' => $with,
                'condition' => "t.wechatId = {$this->wechatInfo->id} $whereType",
                'together' => true
            ),
            //'pagination' => false,
            'pagination' => array(
                'pageSize' => Page::SIZE,
                'pageVar' => 'page'
            ),
        ));
        $this->render('index', array('data' => $dataProvider->getData(), 'pages' => $dataProvider->getPagination(),
            'wechatInfo' => $this->wechatInfo));
    }

    public function actionCreate(){
        $model = new ActiveModel();
        if (isset($_POST['ActiveModel'])) {
            $model->type = Globals::TYPE_REGISTRATION;
            $model->attributes = $_POST['ActiveModel'];
            $model->wechatId = $this->wechatInfo->id;
            $count = $_POST['awardsCount'];
            //奖项处理
            for ($i = 1; $i <= $count; $i++) {
                ${'award' . $i} = $_POST['award' . $i];
                ${'isentity' . $i} = $_POST['isentity' . $i] ? $_POST['isentity' . $i] : 0;
                $awards[$i] = array('name' => ${'award' . $i}, 'isentity' => ${'isentity' . $i});
            }
            $model->awards = serialize($awards);
            if ($model->validate()) {
                $model->save();
                $keywords = $_POST['ActiveModel']['keywords'];
                $isAccurate = $_POST['ActiveModel']['isAccurate'];
                $keywordsArray = explode(',', $keywords);
                $this->saveKeywords($keywordsArray, $model->id, $isAccurate, Globals::TYPE_ACTIVE);
                ShowMessage::success('添加成功', Yii::app()->createUrl('registration'));
            }
        }
        Yii::app()->clientScript->scriptMap['jquery.js'] = false;
        $this->render('create', array('model' => $model, 'wechatId' => $this->wechatInfo->id, 'responseId' => 0,'awards'=>''));
    }

    public function actionUpdate($id){
        $comm = '';
        $model = ActiveModel::model()->findByPk($id,'wechatId=:wechatId',array(':wechatId'=>$this->wechatInfo->id));
        $keywords = KeywordsModel::model()->findAll('responseId=:responseId and type=:type',array(':responseId'=>$id,':type'=>Globals::TYPE_ACTIVE));
        foreach ($keywords as $keywords) {
            $oldKeywords[] = $keywords->name;
            $oldIsAccurate = $keywords->isAccurate;
            $model->keywords .= $comm . $keywords->name;
            $comm = ',';
        }
        $model->isAccurate = $oldIsAccurate;
        if (isset($_POST['ActiveModel'])) {
            $model->attributes = $_POST['ActiveModel'];
            $count = $_POST['awardsCount'];
            //奖项处理
            for ($i = 1; $i <= $count; $i++) {
                ${'award' . $i} = $_POST['award' . $i];
                ${'isentity' . $i} = isset($_POST['isentity' . $i]) ? $_POST['isentity' . $i] : 0;
                $awards[$i] = array('name' => ${'award' . $i}, 'isentity' => ${'isentity' . $i});
            }
            $model->awards = serialize($awards);
            if ($model->validate()) {
                $model->save();
                $keywords = $_POST['ActiveModel']['keywords'];
                $isAccurate = $_POST['ActiveModel']['isAccurate'];
                $keywordsArray = explode(',', $keywords);
                $this->saveKeywords($keywordsArray, $model->id, $isAccurate, Globals::TYPE_ACTIVE, $oldKeywords, $oldIsAccurate);
                ShowMessage::success('添加成功', Yii::app()->createUrl('registration'));
            }
        }
        $awards = unserialize($model->awards);
        $this->render('update', array('model' => $model, 'wechatId' => $this->wechatInfo->id, 'responseId' =>$id,'awards'=>$awards));
    }

    public function actionStatus($id){
        $active = ActiveModel::model()->findByPk($id,'wechatId=:wechatId',array(':wechatId'=>$this->wechatInfo->id));
        $result = -1;
        $msg = '未知错误';
        if(isset($_POST)){
            $status = $_POST['status'];
            if($status!=$active->status){
                $active->status = $status;
                $active->save();
                $result = 0;
                $msg = '';
            }
        }
        die(json_encode(array('result'=>$result,'msg'=>$msg)));
    }

    public function actionDelete($id){
        ActiveModel::model()->deleteByPk($id,'wechatId=:wechatId',array(':wechatId'=>$this->wechatInfo->id));
        //删除关键词
        KeywordsModel::model()->deleteAll('responseId=:responseId and type=:type',array(':responseId'=>$id,':type'=>Globals::TYPE_ACTIVE));
        ShowMessage::success('删除成功',Yii::app()->createUrl('registration'));
    }

    public function actionWinnerList($id){
        $table = 'active_awards';
        $winnerList = array();
        $winner = ActiveAwardsModel::model($table)->findAll('activeId=:activeId and grade>0 and status>0', array(':activeId' => $id));
        foreach ($winner as $w) {
            $winnerList[] = array('telphone' => $w->telphone, 'grade' => $w->grade, 'code' => $w->code, 'datetime' => date('Y-m-d H:i:s', $w->datetime));
        }
        echo json_encode($winnerList);
    }

    public function actionCodes($id){
        $active = ActiveModel::model()->findByPk($id);
        $awards = unserialize($active->awards);
        foreach($awards as $g=>$a){
            if(!$a['isentity']){
                $awardsList[$g] = $a;
            }
        }
        $grades = array_keys($awardsList);
        $grade = Yii::app()->request->getParam('grade',key($awardsList));
        $this->layout = '//layouts/memberList';
        $codeTable = 'active_awards';
        $whereSql = 'activeId=' . $id . ' and grade=' .$grade ;
        $count = Yii::app()->db->createCommand('SELECT COUNT(*) FROM ' . $codeTable . ' where ' . $whereSql)->queryScalar();
        $sql = 'SELECT * FROM ' . $codeTable . ' where ' . $whereSql;
        $dataProvider = new CSqlDataProvider($sql, array(
            'totalItemCount' => $count,
            'sort' => array(
                'attributes' => array(
                    'name',
                )
            ),
            'pagination' => array(
                'pageSize' => Page::SIZE
            )
        ));
        $this->render('code', array('data' => $dataProvider->getData(), 'pages' => $dataProvider->getPagination(),
            'activeId' => $id, 'grades' => $grades,'currentGrade'=>$grade));
    }

    public function actionCodeImport()
    {
        set_time_limit(0);
        $activeId = Yii::app()->request->getParam('activeId');
        $type = Yii::app()->request->getParam('type');
        $grade = Yii::app()->request->getParam('grade');
        $file = $_FILES;
        if ($file && $activeId && $type && $grade) {
            $tmpFile = "upload/" . $_FILES["file"]["name"];
            if (file_exists($tmpFile)) {
                @unlink($_FILES["file"]["name"]);
            } else {
                move_uploaded_file($_FILES["file"]["tmp_name"], $tmpFile);
            }
            $handle = @fopen($tmpFile, "r");
            if ($handle) {
                while (!feof($handle)) {
                    $code = fgets($handle, 4096);
                    //入库
                    if (trim($code)) {
                        $tableName = 'active_awards';
                        $CodeModel = new ActiveAwardsModel($tableName);
                        $CodeModel->activeId = $activeId;
                        $CodeModel->grade = $grade;
                        $CodeModel->code = trim($code);
                        $CodeModel->type = $type;
                        $CodeModel->save();
                    }
                }
                fclose($handle);
            }
            @unlink($tmpFile);
            $msg = "导入成功!";
        } else {
            $msg = '提交错误';
        }
        echo $msg;
    }

    public function actionCodeDelete($id)
    {
        $tableName = 'active_awards';
        $model = ActiveAwardsModel::model($tableName)->findByPk($id);
        /* $codeTable = sprintf(GiftModel::CREATE_CODE_TABLE_NAME, $this->wechatInfo->id);
         GiftCodeModel::model($codeTable)->deleteAll('giftId=:giftId', array(':giftId' => $id));*/
        $model->delete();
        ShowMessage::success('删除成功');
    }

    public function actionCodeTruncate($id){
        $grade = Yii::app()->request->getParam('grade');
        if($grade){
            $activeId=$id;
            $tableName = 'active_awards';
            ActiveAwardsModel::model($tableName)->deleteAll('activeId=:activeId and grade=:grade',
                array(':activeId'=>$activeId,':grade'=>$grade));
        }
        ShowMessage::success('删除成功');
    }
}