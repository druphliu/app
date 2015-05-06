<?php

/**
 * Created by PhpStorm.
 * User: druphliu
 * Date: 2014/12/9
 * Time: 11:04
 */
class ManagerController extends WechatManagerController
{
    public function actionIndex()
    {
        $with = array('active_keywords');
        $whereType = "and t.type='" . Globals::TYPE_WHEEL . "' and active_keywords.type='" . Globals::TYPE_ACTIVE . "'";
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

    public function actionCreate()
    {
        $model = new ActiveModel();
        if (isset($_POST['ActiveModel'])) {
            $model->type = Globals::TYPE_WHEEL;
            $model->attributes = $_POST['ActiveModel'];
            $model->wechatId = $this->wechatInfo->id;
            $count = $_POST['awardsCount'];
            //奖项处理
            for ($i = 1; $i <= $count; $i++) {
                ${'award' . $i} = $_POST['award' . $i];
                ${'isentity' . $i} = isset($_POST['isentity' . $i]) ? $_POST['isentity' . $i] : 0;
                ${'count' . $i} = isset($_POST['count' . $i]) ? $_POST['count' . $i] : 0;
                $awards[$i] = array('name' => ${'award' . $i}, 'isentity' => ${'isentity' . $i},'count'=> ${'count' . $i});
            }
            $model->awards = serialize($awards);
            //处理海报图片
            if($_FILES['focusImg']['error']==0){
                $path = Yii::app()->params['imagePath'].'/'.date('Y').'/'.date('m').'/'.date('d').'/';
                $uploader = new FileUpload($path,$_FILES['focusImg']);
                $uploader->move();
                $return = $uploader->getMessages();
                if(isset($return['name'])){
                    $filename = $return['name'];
                    $filePath = $path.$filename;
                    $model->focusImg = $filePath;
                }
            }
            if ($model->validate()) {
                $model->save();
                $keywords = $_POST['ActiveModel']['keywords'];
                $isAccurate = $_POST['ActiveModel']['isAccurate'];
                $keywordsArray = explode(',', $keywords);
                $this->saveKeywords($keywordsArray, $model->id, $isAccurate, Globals::TYPE_ACTIVE);
                $this->showSuccess('添加成功', Yii::app()->createUrl('wheel'));
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
                ${'count' . $i} = isset($_POST['count' . $i]) ? $_POST['count' . $i] : 0;
                $awards[$i] = array('name' => ${'award' . $i}, 'isentity' => ${'isentity' . $i},'count'=> ${'count' . $i});
            }
            $model->awards = serialize($awards);
            //处理海报图片
            if($_FILES['focusImg']['error']==0){
                $path = Yii::app()->params['imagePath'].'/'.date('Y').'/'.date('m').'/'.date('d').'/';
                $uploader = new FileUpload($path,$_FILES['focusImg']);
                $uploader->move();
                $return = $uploader->getMessages();
                if(isset($return['name'])){
                    $filename = $return['name'];
                    $filePath = $path.$filename;
                    $model->focusImg = $filePath;
                }
            }
            if ($model->validate()) {
                $model->save();
                $keywords = $_POST['ActiveModel']['keywords'];
                $isAccurate = $_POST['ActiveModel']['isAccurate'];
                $keywordsArray = explode(',', $keywords);
                $this->saveKeywords($keywordsArray, $model->id, $isAccurate, Globals::TYPE_ACTIVE, $oldKeywords, $oldIsAccurate);
                $this->showSuccess('添加成功', Yii::app()->createUrl('wheel'));
            }
        }
        $awards = unserialize($model->awards);
        $this->render('update', array('model' => $model, 'wechatId' => $this->wechatInfo->id, 'responseId' =>$id,'awards'=>$awards));
    }

    public function actionDelete($id)
    {
        $model = ActiveModel::model()->findByPk($id);
        KeywordsModel::model()->deleteAll('responseId=:responseId and type=:type', array(':responseId' => $id, ':type' => Globals::TYPE_ACTIVE));
        $model->delete();
        ShowMessage::success('删除成功', Yii::app()->createUrl('scratch'));
    }

    public function actionCodes($id)
    {
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
            'activeId' => $id, 'grades' => $grades,'currentGrade'=>$grade,'active'=>$active));
    }

    public function actionCodeImport()
    {
        set_time_limit(0);
        $activeId = Yii::app()->request->getParam('activeId');
        $type = Yii::app()->request->getParam('type');
        $grade = Yii::app()->request->getParam('grade');
        $file = $_FILES;
        if ($file && $activeId && $type) {
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

    public function actionWinnerList($id)
    {
        $this->layout = '//layouts/iframe';
        $active = ActiveModel::model()->findByPk($id);
        $table = ActiveAwardsInfoModel::model()->getTableName($active->wechatId);
        $tabActiveAwards = ActiveAwardsModel::model()->getTableName($active->wechatId);
        $countSql = 'SELECT COUNT(*) FROM '.$table.' t left join '.$tabActiveAwards.' t2 on t.awardId=t2.id where t2.activeId='.$id;
        $count=Yii::app()->db->createCommand($countSql)->queryScalar();
        $sql='SELECT * FROM '.$table.' t left join '.$tabActiveAwards.' t2 on t.awardId=t2.id where t2.activeId='.$id;
        $dataProvider=new CSqlDataProvider($sql, array(
            'totalItemCount'=>$count,
            'sort'=>array(
                'attributes'=>array(
                    'id',
                ),
            ),
            'pagination'=>array(
                'pageSize'=>Page::SIZE,
            ),
        ));
        $this->render('winnerList', array('data' => $dataProvider->getData(), 'pages' => $dataProvider->getPagination()));

    }


    public function actionCodeTruncate($id){
        $grade = Yii::app()->request->getParam('grade');
        if($grade){
            $activeId=$id;
            $active = ActiveModel::model()->findByPk($activeId);
            $tableName = ActiveAwardsModel::model()->getTableName($active->wechatId);
            ActiveAwardsModel::model($tableName)->deleteAll('activeId=:activeId and grade=:grade',
                array(':activeId'=>$activeId,':grade'=>$grade));
        }
        $this->showSuccess('删除成功');
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
} 