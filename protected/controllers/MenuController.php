<?php

class MenuController extends WechatManagerController
{
    public function actionIndex()
    {
        $list = $child = $childList = array();
        $wechatId = $this->wechatInfo->id;
        $sql = "select * from " . MenuactionModel::model()->tableName() . " where wechatId=" . $wechatId;
        $command = Yii::app()->db->createCommand($sql);
        $menus = $command->queryAll();
        $menu = MenuactionModel::model()->getTree($menus);
        if ($_POST) {
            $output = json_decode($_POST['output']);
            foreach ($output as $a) {
                $list[$a->id]['id'] = $a->id;
                if (isset($a->children)) {
                    foreach ($a->children as $c) {
                        $childList[$c->id] = $a->id;
                        $child[$c->id] = $c->id;
                    }
                    $list[$a->id]['child'] = $child;
                }
            }
            $name = $_POST['name'];
            foreach ($menus as $m) {
                if (isset($name[$m['id']])) {
                    $model = MenuactionModel::model()->findByPk($m['id']);
                    $model->parentId = isset($childList[$m['id']]) ? $childList[$m['id']] : 0;
                    $model->name = $name[$m['id']];
                    if ($_POST['type_' . $m['id']] == MenuactionModel::TYPE_URL) {
                        $model->type = MenuactionModel::TYPE_URL;
                        $model->url = $_POST['value_' . $m['id']];
                    } else {
                        $model->type = MenuactionModel::TYPPE_TEXT;
                        $model->action = $_POST['value_' . $m['id']];
                    }
                    $model->save();
                    unset($name[$m['id']]);
                } else {
                    //删除的
                    MenuactionModel::model()->deleteByPk($m['id']);
                }
            }
            foreach ($name as $id => $name) {
                $newModel = new MenuactionModel();
                if ($_POST['type_' . $id] == MenuactionModel::TYPE_URL) {
                    $newModel->type = MenuactionModel::TYPE_URL;
                    $newModel->url = $_POST['value_' . $id];
                } else {
                    $newModel->type = MenuactionModel::TYPPE_TEXT;
                    $newModel->action = $_POST['value_' . $id];
                }
                $newModel->name = $name;
                $newModel->responseId = 0;
                $newModel->parentId = isset($childList[$id]) ? $childList[$id] : 0;
                $newModel->wechatId = $this->wechatInfo->id;
                $newModel->save();
            }
            ShowMessage::success('报错成功');
        }

        $this->render('index', array('menu' => $menu));
    }

    public function actionAction()
    {
        $setting = SettingModel::model()->find('`key`=:key', array(":key" => GlobalParams::SETTING_KEY_MENU));
        $this->layout = '/layouts/memberList';
        $menu = MenuactionModel::model()->getTree($this->wechatInfo->id);
        $this->render('action', array('menu' => $menu, 'wechatId' => $this->wechatInfo->id, 'setting' => $setting));
    }

    public function actionCreate()
    {
        $status = 1;
        $msg = '添加成功';
        if ($_POST) {
            $parentId = intval($_POST['parentId']);
            $name = $_POST['name'];
            if ($parentId > 0) {
                if (mb_strlen($name, 'utf8') > 7) {
                    $status = -1;
                    $msg = '二级菜单名称不能超过7个汉字';
                };
                //当前菜单的子菜单个数
                $count = MenuModel::model()->count('wechatId = :wechatId and parentId=:parentId', array(':wechatId' => $this->wechatInfo->id, ':parentId' => $parentId));
                if ($count >= 5) {
                    $status = -1;
                    $msg = '二级菜单不能超过5个';
                }
            } else {
                if (mb_strlen($name, 'utf8') > 4) {
                    $status = -1;
                    $msg = '一级菜单名称不能超过4个汉字';
                };
                //一级菜单个数
                $count = MenuModel::model()->count('wechatId = :wechatId and parentId=:parentId', array(':wechatId' => $this->wechatInfo->id, ':parentId' => 0));
                if ($count >= 3) {
                    $status = -1;
                    $msg = '一级菜单不能超过3个';
                }
            }
            if ($status == 1) {
                $modelAction = new MenuactionModel();
                $modelAction->action = $_POST['type'] == GlobalParams::TYPE_URL ? $_POST['url'] : $_POST['action'];
                $model = new MenuModel();
                $model->name = $name;
                $model->wechatId = $this->wechatInfo->id;
                $model->type = $_POST['type'];
                $model->parentId = $_POST['parentId'] ? $_POST['parentId'] : 0;
                if ($model->validate() && $modelAction->validate()) {
                    $model->save();
                    $modelAction->menuId = $model->id;
                    $modelAction->save();
                } else {
                    $status = -1;
                    $error = $model->getErrors();
                    if ($error) {
                        foreach ($error as $e) {
                            $msg .= $e[0];
                        }
                    }
                }
            }
        }
        echo json_encode(array('status' => $status, 'msg' => $msg));
    }

    public function actionDelete($id)
    {
        $sql = 'delete a,b from ' . MenuactionModel::model()->tableName() . ' as a left join ' . MenuModel::model()->tableName() . '
       as b on a.menuId=b.id where b.id=' . $id . ' or b.parentId=' . $id;
        $command = Yii::app()->db->createCommand($sql);
        $command->execute();
        ShowMessage::success('删除成功');
    }

    public function actionUpdate($id)
    {
        $model = MenuModel::model()->with('menu_action')->findByPk($id);
        if ($_POST) {
            $status = 1;
            $msg = '更新成功';
            $model->name = $_POST['name'];
            $model->type = $_POST['type'];
            if (!$model->parentId && $_POST['parentId']) {
                $status = -1;
                $msg = '此菜单含有子菜单，不能做此修改';
            } else {
                $model->parentId = $_POST['parentId'] ? $_POST['parentId'] : 0;
                $modelAction = MenuactionModel::model()->findByPk($model->menu_action->id);
                $modelAction->action = $_POST['type'] == GlobalParams::TYPE_URL ? $_POST['url'] : $_POST['action'];
                if ($model->validate() && $modelAction->validate()) {
                    $modelAction->save();
                    $model->save();
                } else {
                    $status = -1;
                    $error = $model->getErrors();
                    if ($error) {
                        foreach ($error as $e) {
                            $msg .= $e[0];
                        }
                    }
                }
            }
            echo json_encode(array('status' => $status, 'msg' => $msg));
        } else {
            $result['name'] = $model->name;
            $result['type'] = $model->type;
            $result['url'] = $result['action'] = isset($model->menu_action->action) ? $model->menu_action->action : '';
            $result['actionId'] = isset($model->menu_action->id) ? $model->menu_action->id : 0;
            echo json_encode($result);
        }
    }

    public function actionTextReplay()
    {
        $responseId = Yii::app()->request->getParam('responseId');
        $actionId = Yii::app()->request->getParam('actionId');
        $content = '';
        $status = 1;
        if ($responseId) {
            $model = TextReplayModel::model()->findByPk($responseId);
            $content = $model->content;
        }
        if (isset($_POST['content']) && $actionId) {
            $model = isset($model) ? $model : new TextReplayModel();
            $model->wechatId = $this->wechatInfo->id;
            $model->type = GlobalParams::TYPE_TEXT;
            $model->content = $_POST['content'];
            if ($model->validate()) {
                $model->save();
                $actionModel = MenuactionModel::model()->findByPk($actionId);
                $actionModel->responseId = $model->id;
                $actionModel->save();
                $status = 1;
                $content = '编辑成功';
            } else {
                $status = -1;
                foreach ($model->getErrors() as $e) {
                    $content .= $e[0];
                }
            }
        }
        echo json_encode(array('status' => $status, 'content' => $content));
    }

    public function actionImageTextReplay($actionId)
    {
        $focus = $imageTextList = $dataList = array();
        $this->layout = '//layouts/iframe';
        $actionModel = MenuactionModel::model()->findByPk($actionId);
        $responseId = $actionModel->responseId;
        $imageTextList = array();
        if ($responseId) {
            $focus = ImagetextreplayModel::model()->findByPk($responseId);
            $imageTextList = ImagetextreplayModel::model()->findAll('parentId=:parentId', array(':parentId' => $responseId));
            $dataList = CHtml::listData($imageTextList, 'id', 'id');
        }
        if (isset($_POST['count'])) {
            $validate = true;
            $msg = '';
            $count = $_POST['count'];
            for ($i = 1; $i <= $count; $i++) {
                $title = $_POST['title' . $i];
                $summary = $_POST['summary' . $i];
                $imgUrl = $_POST['src' . $i];
                $url = $_POST['url' . $i];
                $id = isset($_POST['id' . $i]) ? $_POST['id' . $i] : 0;
                $formName = 'form' . $i;
                if ($id) {
                    $$formName = ImagetextreplayModel::model()->findByPk($id);
                    if(in_array($id,$dataList))
                        unset($dataList[$id]);
                }
                $$formName = isset($$formName) ? $$formName : new ImagetextreplayModel();
                $$formName->wechatId = $this->wechatInfo->id;
                $$formName->type = GlobalParams::TYPE_MENU;
                $$formName->title = $title;
                $$formName->description = $summary;
                $$formName->imgUrl = $imgUrl;
                $$formName->url = $url;
                if (!$$formName->validate()) {
                    $validate = false;
                    foreach ($$formName->getErrors() as $e) {
                        $msg .= $e[0];
                    }
                };
            }
            if ($validate) {
                $responseId = 0;
                for ($i = 1; $i <= $count; $i++) {
                    $formName = 'form' . $i;
                    $$formName->parentId = $responseId;
                    $$formName->save();
                    if ($i == 1) {
                        $responseId = $$formName->id;
                        $actionModel->responseId = $responseId;
                        $actionModel->save();
                    }
                }
                //删除已经删除的数据
                if($dataList){
                    foreach($dataList as $id){
                        ImagetextreplayModel::model()->deleteByPk($id);
                    }
                }
                echo json_encode(array('status' => 1, 'msg' => $msg));
            } else {
                echo json_encode(array('status' => -1, 'msg' => $msg));
            }
            return;
        }
        $this->render('imageText', array('imageTextList' => $imageTextList, 'focus' => $focus));

    }

    public function actionDeleteImgList($id)
    {
        $status = -1;
        $textImg = ImagetextreplayModel::model()->findByPk($id);
        if ($textImg) {
            $textImg->delete();
            $status = 1;
        }
        echo json_encode(array('status' => $status));
    }

    public function actionGetDropDownList()
    {
        $option = '';
        $parentId = Yii::app()->request->getParam('parentId');
        $menu = MenuactionModel::model()->getTree($this->wechatInfo->id);
        if (count($menu) < 3 || isset($_GET['parentId'])) {
            $option = '<option value="0">一级菜单</option>';
        }
        if($menu){
            foreach ($menu as $m) {
                if (!(isset($m['child']) && count($m['child']) >= 5)) {
                    $select = $m['id'] == $parentId ? 'selected="selected"' : '';
                    $option .= '<option value="' . $m['id'] . '" ' . $select . '>' . $m['name'] . '</option>';
                }
            }
        }
        echo $option;
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