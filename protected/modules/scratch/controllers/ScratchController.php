<?php

/**
 * Created by PhpStorm.
 * User: druphliu
 * Date: 2014/12/9
 * Time: 11:04
 */
class ScratchController extends WechatManagerController
{
    public function actionIndex()
    {
        $type = Yii::app()->request->getParam('type');
        $type = $type ? $type : Globals::TYPE_KEYWORDS;
        switch ($type) {
            case Globals::TYPE_KEYWORDS:
                $with = array('scratch_keywords');
                $whereType = "and t.type='" . Globals::TYPE_KEYWORDS . "'
                and scratch_keywords.type='" . Globals::TYPE_SCRATCH . "'";
                break;
            case Globals::TYPE_MENU:
                $with = array('scratch_menuaction');
                $whereType = "and t.type='" . Globals::TYPE_MENU . "'";
                break;
        }
        $this->layout = '//layouts/memberList';
        $dataProvider = new CActiveDataProvider('ScratchModel', array(
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
            'type' => $type, 'wechatInfo' => $this->wechatInfo));
    }

    public function actionCreate()
    {
        $menuList = array();
        $type = Yii::app()->request->getParam('type');
        $type = $type ? $type : Globals::TYPE_KEYWORDS;
        if ($type == Globals::TYPE_MENU) {
            //取menu的下拉列表
            $menuList = MenuModel::model()->getMenuDropDownList($this->wechatInfo->id, Globals::TYPE_GIFT);
        }
        $model = new ScratchModel();
        if (isset($_POST['ScratchModel'])) {
            $model->type = $type;
            $model->attributes = $_POST['ScratchModel'];
            $model->wechatId = $this->wechatInfo->id;
            if ($_FILES) {
                $file = array('name' => $_FILES['ScratchModel']['name']['backgroundPic'],
                    'type' => $_FILES['ScratchModel']['type']['backgroundPic'],
                    'tmp_name' => $_FILES['ScratchModel']['tmp_name']['backgroundPic'],
                    'error' => $_FILES['ScratchModel']['error']['backgroundPic'],
                    'size' => $_FILES['ScratchModel']['size']['backgroundPic']);
                $uploadPath = Yii::app()->params['scratchPath'] . "/" . $this->wechatInfo->id . '/';
                $fileUpload = new FileUpload($uploadPath, $file);
                $fileUpload->move();
                $result = $fileUpload->getMessages();
                if (isset($result['name'])) {
                    $backgroundPic = $result['name'];
                } else {
                    ShowMessage::Error(current($result));
                }
                $file = array('name' => $_FILES['ScratchModel']['name']['button'],
                    'type' => $_FILES['ScratchModel']['type']['button'],
                    'tmp_name' => $_FILES['ScratchModel']['tmp_name']['button'],
                    'error' => $_FILES['ScratchModel']['error']['button'],
                    'size' => $_FILES['ScratchModel']['size']['button']);
                $uploadPath = Yii::app()->params['scratchPath'] . "/" . $this->wechatInfo->id . '/';
                $fileUpload = new FileUpload($uploadPath, $file);
                $fileUpload->move();
                $result = $fileUpload->getMessages();
                if (isset($result['name'])) {
                    $button = $result['name'];
                } else {
                    ShowMessage::Error(current($result));
                }
            }
            //奖项处理
            for ($i = 1; $i <= 4; $i++) {
                ${'award' . $i} = $_POST['award' . $i];
                ${'isentity' . $i} = $_POST['isentity' . $i] ? $_POST['isentity' . $i] : 0;
                $awards[$i] = array('name' => ${'award' . $i}, 'isentity' => ${'isentity' . $i});
            }
            $model->awards = serialize($awards);
            $model->backgroundPic = $backgroundPic;
            $model->button = $button;
            $model->ispaward = $_POST['ScratchModel']['ispaward'];
            if ($model->validate()) {
                $model->save();
                switch ($type) {
                    case Globals::TYPE_KEYWORDS:
                        $keywords = $_POST['ScratchModel']['keywords'];
                        $isAccurate = $_POST['ScratchModel']['isAccurate'];
                        $keywordsArray = explode(',', $keywords);
                        foreach ($keywordsArray as $k) {
                            $keywordsModel = new KeywordsModel();
                            $keywordsModel->responseId = $model->id;
                            $keywordsModel->type = Globals::TYPE_SCRATCH;
                            $keywordsModel->isAccurate = $isAccurate;
                            $keywordsModel->name = $k;
                            $keywordsModel->wechatId = $this->wechatInfo->id;
                            $keywordsModel->save();
                        }
                        break;
                    case Globals::TYPE_MENU:
                        $menuId = $_POST['ScratchModel']['action'];
                        $menuActionModel = MenuactionModel::model()->find('menuId=:menuId', array(':menuId' => $menuId));
                        $menuActionModel->responseId = $model->id;
                        $menuActionModel->save();
                        break;
                }
                ShowMessage::success('添加成功', Yii::app()->createUrl('scratch', array('type' => $type)));
            }
        }
        Yii::app()->clientScript->scriptMap['jquery.js'] = false;
        $this->render('create', array('model' => $model, 'type' => $type ? $type : GiftModel::TYPE_KEYWORDS,
            'wechatId' => $this->wechatInfo->id, 'responseId' => 0, 'menuList' => $menuList));
    }

    public function actionDelete($id)
    {
        $model = ScratchModel::model()->findByPk($id);
        //删除关键字或者menu action
        switch ($model->type) {
            case GiftModel::TYPE_KEYWORDS:
                KeywordsModel::model()->deleteAll('responseId=:responseId and type=:type', array(':responseId' => $id, ':type' => Globals::TYPE_SCRATCH));
                break;
            case GiftModel::TYPE_MENU:
                $menuactionModel = MenuactionModel::model()->find('responseId=:responseId', array('responseId' => $model->id));
                $menuactionModel->responseId = 0;
                $menuactionModel->save();
                break;
        }
        $model->delete();
        ShowMessage::success('删除成功', Yii::app()->createUrl('scratch'));
    }

    public function actionCodes($id)
    {
        $this->layout = '//layouts/memberList';
        $type = Yii::app()->request->getParam('type',Globals::CODE_TYPE_LEGAL);
        $codeTable = 'scratch_awards';
        $whereSql = 'scratchId=' . $id .' and type='.$type;
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
            'scratchId' => $id, 'type' => $type));
    }

    public function actionCodeImport()
    {
        set_time_limit(0);
        $scratchId = Yii::app()->request->getParam('scratchId');
        $type = Yii::app()->request->getParam('type',Globals::CODE_TYPE_LEGAL);
        $file = $_FILES;
        if ($file && $scratchId) {
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
                        $tableName = 'scratch_awards';
                        $CodeModel = new ScratchAwardsModel($tableName);
                        $CodeModel->scratchId = $scratchId;
                        $CodeModel->grade = 0;
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
} 