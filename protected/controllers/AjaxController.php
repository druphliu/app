<?php

/**
 * Created by app.
 * User: druphliu
 * Date: 14-10-15
 * Time: 下午5:25
 */
class AjaxController extends Controller
{
    /**
     * 关键字重复检测
     */
    public function actionCheckKeywords()
    {
        $exitKeywords = '';
        $result = 1;
        $msg = "";
        $keyword = Yii::app()->request->getParam('keywords');
        $responseId = Yii::app()->request->getParam('responseId');
        $wechatId = Yii::app()->request->getParam('wechatId');
        $type = Yii::app()->request->getParam('type');
        if ($keyword && $wechatId) {
            $isAccurate = Yii::app()->request->getParam('isAccurate');
            $keywordArray = explode(',', $keyword);
            foreach ($keywordArray as $k) {
                $keywords = Yii::app()->db->createCommand()
                    ->select('name, isAccurate,type, responseId')
                    ->from('keywords')
                    ->where(array('and', 'wechatId=' . $wechatId,
                        array('like', 'name', array('%' . $k . '%'))))
                    ->queryAll();
                if ($keywords) {
                    foreach ($keywords as $k) {
                        switch ($k['isAccurate']) {
                            case 1:
                                //当前关键词精准匹配
                                if ($isAccurate) {
                                    //新加关键字为精准匹配
                                    if ($k['name'] == $keyword) {
                                        if (!($responseId && ($k['type'] == $type && $k['responseId'] == $responseId)))
                                            $exitKeywords .= $k['name'] . ',';
                                    }
                                } else {
                                    //新加关键字模糊匹配
                                    if (mb_strpos($k['name'], $keyword) !== false) {
                                        if (!($responseId && ($k['type'] == $type && $k['responseId'] == $responseId)))
                                            $exitKeywords .= $k['name'] . ',';
                                    }
                                }
                                break;
                            case 0:
                                //当前关键词模糊匹配
                                if ($isAccurate) {
                                    //新加关键字为精准匹配
                                    if (mb_strpos($keyword, $k['name']) !== false) {
                                        if (!($responseId && ($k['type'] == $type && $k['responseId'] == $responseId)))
                                            $exitKeywords .= $k['name'] . ',';
                                    }
                                } else {
                                    //新加关键字模糊匹配
                                    if (mb_strpos($keyword, $k['name']) !== false || mb_strpos($k['name'], $keyword) !== false) {
                                        if (!($responseId && ($k['type'] == $type && $k['responseId'] == $responseId)))
                                            $exitKeywords .= $k['name'] . ',';
                                    }
                                }
                                break;
                        }
                    }
                    if ($exitKeywords) {
                        $result = -1;
                        $msg = '与关键词' . $exitKeywords . '冲突了';
                    }
                }
            }
        } else {
            $result = 0;
            $msg = '关键字不能为空';
        }
        echo json_encode(array('result' => $result, 'msg' => $msg));
    }

    /**
     * 礼包码活动开关
     * @param $id
     */
    public function actionGiftStatus($id)
    {
        $status = Yii::app()->request->getParam('status');
        $model = GiftModel::model()->findByPk($id);
        $model->status = in_array($status, array(0, 1)) ? $status : 0;
        $model->save();
        echo json_encode(array('result' => 0));
    }

    /**
     * 转接回复开关
     * @param $id
     */
    public function actionOpenReplayStatus($id)
    {
        $status = Yii::app()->request->getParam('status');
        $model = OpenReplayModel::model()->findByPk($id);
        $model->status = in_array($status, array(0, 1)) ? $status : 0;
        $model->save();
        echo json_encode(array('result' => 0));
    }

    /**
     * 转接平台状态检查
     * @param $id
     */
    public function actionOpenStatus($id)
    {
        $result = 0;
        $model = OpenPlatformModel::model()->findByPk($id);
        if ($model) {
            $echostr = 'hello';
            $wechatApi = new WechatApi($model->token);
            $url = $wechatApi->buildSignUrl($model->apiUrl, array('echostr' => $echostr));
            $content = HttpRequest::sendHttpRequest($url);
            if ($content['content'] == $echostr)
                $result = 1;
            $model->status = $result;
            $model->save();
        }
        echo json_encode(array('result' => $result));
    }

    /**
     * 菜单值重复检查
     */
    public function actionCheckMenuName()
    {
        $name = Yii::app()->request->getParam('name');
        $wechatId = Yii::app()->request->getParam('wechatId');
        $id = intval(isset($_POST['id']) ? $_POST['id'] : $_GET['id']);
        $result = 'true';
        $criteria = new CDbCriteria;
        $criteria->condition = 'wechatId=:wechatId and name=:name';
        $criteria->condition = $id ? $criteria->condition . ' and id<>' . $id : $criteria->condition;
        $criteria->params = array(':wechatId' => $wechatId, ':name' => $name);
        $actionExit = MenuModel::model()->find($criteria);
        if ($actionExit) {
            $result = 'false';
        }
        echo $result;
    }
    /**
     * 菜单值重复检查
     */
    public function actionCheckMenuKeywords()
    {
        $name = Yii::app()->request->getParam('name');
        $wechatId = Yii::app()->request->getParam('wechatId');
        $id = intval(isset($_POST['id']) ? $_POST['id'] : $_GET['id']);
        $result = 'true';
        /*$criteria = new CDbCriteria;
        $criteria->condition = 'wechatId=:wechatId and name=:name';
        $criteria->condition = $id ? $criteria->condition . ' and id<>' . $id : $criteria->condition;
        $criteria->params = array(':wechatId' => $wechatId, ':name' => $name);
        $actionExit = MenuModel::model()->find($criteria);
        if ($actionExit) {
            $result = 'false';
        }*/
        echo $result;
    }

    /**
     * 菜单编辑模拟关键系，搜索关键词
     */
    public function actionGetKeywords()
    {
        $search = isset($_POST['name']) ? $_POST['name'] : $_GET['name'];
        $callback = $_GET['callback'];
        $wechatId = isset($_POST['wechatId']) ? $_POST['wechatId'] : $_GET['wechatId'];
        if (!$search || !$callback) {
            return;
        }
        $sql = "select name,id from " . KeywordsModel::model()->tableName() . " where  wechatId=" . $wechatId . " and  name like '%$search%'";
        $result = yii::app()->db->createCommand($sql);
        $keywords = $result->queryAll();
        echo $callback . "(" . json_encode(array('total' => count($keywords), 'keywords' => $keywords)) . ")";
    }

    /**
     * 更新菜单
     */
    public function actionUpdateMenu($wechatId)
    {
        $status = -1;
        $token = $this->_getToken($wechatId);
        $tokenValue = $token['tokenValue'];
        if ($tokenValue) {
            //更新菜单
            $menu = MenuModel::model()->getTree($wechatId);
            if ($menu) {
                foreach ($menu as $m) {
                    if (isset($m['child']) && $m['child']) {
                        foreach ($m['child'] as $ch) {
                            if ($ch['type'] == Globals::TYPE_URL) {
                                $subV = array('type' => 'view', 'url' => $ch['url']);
                            } else {
                                $subV = array('type' => 'click', 'key' => urlencode($ch['name']));
                            }
                            $subV['name'] = urlencode($ch['name']);
                            $sub[] = $subV;
                        }
                        $t = array(
                            'name' => urlencode($m['name']),
                            'sub_button' => $sub);
                        unset($sub);
                    } else {
                        if ($m['type'] == Globals::TYPE_URL) {
                            $t = array('type' => 'view', 'url' => $m['url']);
                        } else {
                            $t = array('type' => 'click', 'key' => urlencode($m['name']));
                        }
                        $t['name'] = urlencode($m['name']);
                    }
                    $buttonValue['button'][] = $t;
                }

                $menuValue = stripslashes(urldecode(json_encode($buttonValue)));
                $url = sprintf(Globals::MENU_UPDATE_URL, $tokenValue);
                $result = HttpRequest::sendHttpRequest($url, $menuValue, 'POST');
                $resultData = json_decode($result['content']);
                $status = $resultData->errcode == Globals::WECHAT_RESPONSE_OK ? 1 : -1;
                $msg = $resultData->errcode == Globals::WECHAT_RESPONSE_OK ? '' : Globals::$wechatErrorCode[$resultData->errcode];
                if ($status == 1) {
                    $settingMenuModel = SettingModel::model()->find("wechatId = :wechatId and `key`=:key",
                        array(':wechatId' => $wechatId, ':key' => Globals::SETTING_KEY_MENU));
                    if (!$settingMenuModel) {
                        $settingMenuModel = new SettingModel();
                        $settingMenuModel->key = Globals::SETTING_KEY_MENU;
                        $settingMenuModel->wechatId = $wechatId;
                    }
                    $settingMenuModel->created_at = time();
                    $settingMenuModel->value = $menuValue;
                    $settingMenuModel->save();
                }
            } else {
                $msg = '菜单为空';
            }

        } else {
            $msg = '获取token异常';
        }
        echo json_encode(array('status' => $status, 'msg' => $msg));
    }

    public function actionDeleteMenu($wechatId)
    {
        $status = -1;
        $token = Globals::getToken($wechatId);
        $tokenValue = $token['tokenValue'];
        if ($tokenValue) {
            $url = sprintf(Globals::MENU_DELETE_URL, $tokenValue);
            $result = HttpRequest::sendHttpRequest($url);
            $resultData = json_decode($result['content']);
            $status = $resultData->errcode == Globals::WECHAT_RESPONSE_OK ? 1 : -1;
            $msg = $resultData->errcode == Globals::WECHAT_RESPONSE_OK ? '' : Globals::$wechatErrorCode[$resultData->errcode];
        } else {
            $msg = '获取token异常';
        }
        echo json_encode(array('status' => $status, 'msg' => $msg));
    }


}