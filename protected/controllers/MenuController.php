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
        $setting = SettingModel::model()->find('`key`=:key', array(":key" => Globals::SETTING_KEY_MENU));
        $this->layout = '/layouts/memberList';
        $menu = MenuModel::model()->getTree($this->wechatInfo->id);
        $this->render('index', array('menu' => $menu, 'wechatId' => $this->wechatInfo->id, 'setting' => $setting));
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
                $model = new MenuModel();
                $model->name = $name;
                $model->wechatId = $this->wechatInfo->id;
                $model->type = $_POST['type'];
                $model->parentId = $_POST['parentId'] ? $_POST['parentId'] : 0;
                if($model->type == Globals::TYPE_KEYWORDS){
                    $model->keywordsId = $_POST['keywordsId'];
                }else{
                    $model->url = $_POST['url'];
                }
                if ($model->validate()) {
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
        }
        echo json_encode(array('status' => $status, 'msg' => $msg));
    }

    public function actionDelete($id)
    {
        if(MenuModel::model()->deleteByPk($id)){
            ShowMessage::success('删除成功');
        }
    }

    public function actionUpdate($id)
    {
        $model = MenuModel::model()->findByPk($id);
        if ($_POST) {
            $status = 1;
            $msg = '更新成功';
            $model->name = $_POST['name'];
            $model->type = $_POST['type'];
            if ($model->parentId>0 && $_POST['parentId']) {
                $status = -1;
                $msg = '此菜单含有子菜单，不能做此修改';
            } else {
                $model->parentId = isset($_POST['parentId']) ? $_POST['parentId'] : 0;
                $model->keywordsId =isset($_POST['keywordsId']) ? $_POST['keywordsId'] : 0;
                $model->url =isset($_POST['url']) ? $_POST['url'] : 0;
                if ($model->validate()) {
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
            $result['keywordsName'] = '';
            if($model->type==Globals::TYPE_KEYWORDS){
                $keywords = KeywordsModel::model()->findByPk($model->keywordsId);
                $result['keywordsName'] = $keywords->name;
            }
            $result['url'] = $model->url;
            $result['keywordsId'] = $model->keywordsId;
            $result['id']=$model->id;

            echo json_encode($result);
        }
    }

    public function actionGetDropDownList()
    {
        $option = '';
        $parentId = Yii::app()->request->getParam('parentId');
        $menu = MenuModel::model()->getTree($this->wechatInfo->id);
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