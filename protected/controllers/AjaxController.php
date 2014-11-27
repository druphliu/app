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
        $keyword = Yii::app()->request->getParam('keyword');
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
    public function actionCheckAction()
    {
        $action = Yii::app()->request->getParam('action');
        $wechatId = Yii::app()->request->getParam('wechatId');
        $actionId = Yii::app()->request->getParam('actionId');
        $result = 'true';
        $msg = "";
        $criteria = new CDbCriteria;
        if ($actionId) {
            $criteria->condition = 'action_menu.wechatId=:wechatId and action=:action and action_menu.type<>:type and t.id<>:id';
            $criteria->params = array(':wechatId' => $wechatId, ':action' => $action, ':type' => GlobalParams::TYPE_URL, ':id' => $actionId);
        } else {
            $criteria->condition = 'action_menu.wechatId=:wechatId and action=:action and action_menu.type<>:type';
            $criteria->params = array(':wechatId' => $wechatId, ':action' => $action, ':type' => GlobalParams::TYPE_URL);
        }
        $actionExit = MenuactionModel::model()->with('action_menu')->find($criteria);
        if ($actionExit) {
            $result = 'false';
            $msg = '菜单值与菜单' . $actionExit->action_menu->name . '冲突了';
        }
        echo $result;
    }

    /**
     * 更新菜单
     */
    public function actionUpdateMenu()
    {
        $status = -1;
        $msg = '参数有误';
        $wechatId = Yii::app()->request->getParam('wechatId');
        if ($wechatId) {
            $tokenModel = SettingModel::model()->find("wechatId = :wechatId and `key`=:key",
                array(':wechatId' => $wechatId, ':key' => GlobalParams::SETTING_KEY_ACCESS_TOKEN));
            if ($tokenModel) {
                if ((time() - $tokenModel->created_at) > WechatToken::EXPIRES_IN) {
                    $tokenObj = new WechatToken(GlobalParams::APP_ID, GlobalParams::APP_SECRET);
                    $token = $tokenObj->getToken();
                    if ($token['status'] == WechatToken::OK) {
                        $tokenValue = $token['result'];
                        //update token
                        $tokenModel->value = $tokenValue;
                        $tokenModel->created_at = time();
                        $tokenModel->save();
                    } else {
                        $msg = $token['result'];
                    }
                } else {
                    $tokenValue = $tokenModel->value;
                }
                if ($tokenValue) {
                    //更新菜单
                    $menu = MenuactionModel::model()->getTree($wechatId);
                    foreach ($menu as $m) {
                        if (isset($m['child'])) {
                            foreach ($m['child'] as $ch) {
                                if ($ch['type'] == GlobalParams::TYPE_URL) {
                                    $subV = array('type' => 'view', 'url' => $ch['action']);
                                } else {
                                    $subV = array('type' => 'click', 'key' => $ch['action']);
                                }
                                $subV['name'] = $ch['name'];
                                $sub[] = $subV;
                            }
                            $t = array(
                                'name' => $m['name'],
                                'sub_button' => $sub);
                            unset($sub);
                        } else {
                            if ($m['type'] == GlobalParams::TYPE_URL) {
                                $urlInfo = UrlModel::model()->findByPk($m['responseId']);
                                $t = array('type' => 'view', 'url' => $urlInfo->url);
                            } else {
                                $t = array('type' => 'click', 'key' => $m['action']);
                            }
                            $t['name'] = $m['name'];
                        }
                        $buttonValue['button'][] = $t;
                    }
                }
                $menuValue = stripslashes(preg_replace("#\\\u([0-9a-f]+)#ie", "iconv('UCS-2', 'UTF-8', pack('H4', '\\1'))", json_encode($buttonValue)));
                $url = sprintf(GlobalParams::MENU_UPDATE_URL, $tokenValue);
                $result = HttpRequest::sendHttpRequest($url, $menuValue, 'POST');
                $resultData = json_decode($result['content']);
                $status = $resultData->errcode == GlobalParams::WECHAT_RESPONSE_OK ? 1 : -1;
                $msg = $resultData->errcode == GlobalParams::WECHAT_RESPONSE_OK ? '' : GlobalParams::$wechatErrorCode[$resultData->errcode];
                if ($status == 1) {
                    $settingMenuModel = SettingModel::model()->find("wechatId = :wechatId and `key`=:key",
                        array(':wechatId' => $wechatId, ':key' => GlobalParams::SETTING_KEY_MENU));
                    $settingMenuModel->created_at = time();
                    $settingMenuModel->value = $menuValue;
                    $settingMenuModel->save();
                }
            }else{
                $status = -1;
                $msg = '还未添加菜单';
            }
        };
        echo json_encode(array('status' => $status, 'msg' => $msg));
    }

}