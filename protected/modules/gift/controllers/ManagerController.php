<?php

class ManagerController extends WechatManagerController
{
	public function actionIndex()
	{
        $with = array('gift_keywords');
        $whereType = " and gift_keywords.type='" . GiftModel::GIFT_TYPE . "'";
        $this->layout = '//layouts/memberList';
        $dataProvider = new CActiveDataProvider('GiftModel', array(
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
        $this->render('gift', array('data' => $dataProvider->getData(), 'pages' => $dataProvider->getPagination(),
            'wechatInfo' => $this->wechatInfo));
	}

    public function actionCreate()
    {
        $model = new GiftModel();
        if (isset($_POST['GiftModel'])) {
            $model->attributes = $_POST['GiftModel'];
            $model->wechatId = $this->wechatInfo->id;
            if ($model->validate()) {
                $model->save();
                //创建礼包码表
                $result = GiftModel::model()->createCodeTable($this->wechatInfo->id);
                $keywords = $_POST['GiftModel']['keywords'];
                $isAccurate = $_POST['GiftModel']['isAccurate'];
                $keywordsArray = explode(',', $keywords);
                $this->saveKeywords($keywordsArray,$model->id,$isAccurate,Globals::TYPE_GIFT);
                if ($result == GiftModel::TABLE_CREATE_FAILED) {
                    $this->showError('创建异常，请重新编辑此信息', Yii::app()->createUrl('gift'));
                } else {
                    $this->showSuccess('添加成功', Yii::app()->createUrl('gift'));
                }

            }
        }
        Yii::app()->clientScript->scriptMap['jquery.js'] = false;
        $this->render('giftCreate', array('model' => $model, 'wechatId' => $this->wechatInfo->id, 'responseId' => 0,));
    }

    public function actionUpdate($id)
    {
        $keyword = $common = '';
        $model = GiftModel::model()->findByPk($id);
        $keywords = KeywordsModel::model()->findAll('type=:type and responseId=:responseId',
            array(':type' => GiftModel::GIFT_TYPE, ':responseId' => $id));
        foreach ($keywords as $k) {
            $oldKeywords[] = $k->name;
            $oldIsAccurate = $k->isAccurate;
            $keyword .= $common . $k->name;
            $common = ',';
        }
        $model->keywords = $keyword;
        $model->isAccurate = $oldIsAccurate;
        if (isset($_POST['GiftModel'])) {
            $model->attributes = $_POST['GiftModel'];
            if ($model->validate()) {
                $isAccurate = $_POST['GiftModel']['isAccurate'];
                $keywordsArray = explode(',', $_POST['GiftModel']['keywords']);
                $this->saveKeywords($keywordsArray,$model->id,$isAccurate,Globals::TYPE_GIFT,$oldKeywords,$oldIsAccurate);
                //更新是否精准匹配字段
                $model->save();
                //更新code表，防止礼包时礼包码表未创建成功问题
                $result = GiftModel::model()->createCodeTable($this->wechatInfo->id);
                if ($result == GiftModel::TABLE_CREATE_FAILED) {
                   $this->showError('数据异常，请再次编辑一下');
                } else {
                    $this->showSuccess('编辑成功', $this->createUrl('manager/index'));
                }

            }
        }
        Yii::app()->clientScript->scriptMap['jquery.js'] = false;
        $this->render('giftUpdate', array('model' => $model, 'wechatId' => $this->wechatInfo->id, 'responseId' => $id));
    }

    public function actionStart($id)
    {
        $model = GiftModel::model()->findByPk($id);
        $model->status = 1;
        $model->save();
        $this->showSuccess('已开启');
    }

    public function actionStop($id)
    {
        $model = GiftModel::model()->findByPk($id);
        $model->status = 0;
        $model->save();
        $this->showSuccess('已停止');
    }

    public function actionDelete($id)
    {
        $model = GiftModel::model()->findByPk($id);
        $codeTable = sprintf(GiftModel::CREATE_CODE_TABLE_NAME, $this->wechatInfo->id);
        GiftCodeModel::model($codeTable)->deleteAll('giftId=:giftId', array(':giftId' => $id));
        //删除关键字
        KeywordsModel::model()->find('responseId=:responseId and wechatId=:wechatId',array(':responseId'=>$id,':wechatId'=>$this->wechatInfo->id))->delete();
        $model->delete();
        $this->showSuccess('删除成功', $this->createUrl('gift'));
    }

    public function actionCodes($id)
    {
        $this->layout = '//layouts/memberList';
        $codeTable = sprintf(GiftModel::CREATE_CODE_TABLE_NAME, $this->wechatInfo->id);
        $whereSql = 'giftId=' . $id;
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
        $this->render('giftCode', array('data' => $dataProvider->getData(), 'pages' => $dataProvider->getPagination(), 'giftId' => $id));
    }

    public function actionCodeImport()
    {
        set_time_limit(0);
        $giftId = Yii::app()->request->getParam('giftId');
        $file = $_FILES;
        if ($file && $giftId) {
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
                    $tableName = sprintf(GiftModel::CREATE_CODE_TABLE_NAME, $this->wechatInfo->id);
                    $CodeModel = new GiftCodeModel($tableName);
                    $CodeModel->giftId = $giftId;
                    $CodeModel->code = trim($code);
                    $CodeModel->save();
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
        $tableName = sprintf(GiftModel::CREATE_CODE_TABLE_NAME, $this->wechatInfo->id);
        $model = GiftCodeModel::model($tableName)->findByPk($id);
        /* $codeTable = sprintf(GiftModel::CREATE_CODE_TABLE_NAME, $this->wechatInfo->id);
         GiftCodeModel::model($codeTable)->deleteAll('giftId=:giftId', array(':giftId' => $id));*/
        $model->delete();
        $this->showSuccess('删除成功');
    }

    public function actionCodeTruncate($id){
        $giftId=$id;
        $tableName = sprintf(GiftModel::CREATE_CODE_TABLE_NAME, $this->wechatInfo->id);
        GiftCodeModel::model($tableName)->deleteAll('giftId=:giftId',array(':giftId'=>$giftId));
        $this->showSuccess('删除成功');
    }
}