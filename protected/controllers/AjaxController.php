<?php

/**
 * Created by app.
 * User: druphliu
 * Date: 14-10-15
 * Time: 下午5:25
 */
class AjaxController extends Controller
{
    public function ActionCheckKeywords()
    {
        $exitKeywords = '';
        $result = 1;
        $count = 0;
        $msg = "";
        $keyword = Yii::app()->request->getParam('keyword');
        $wechatId = Yii::app()->request->getParam('wechatId');
        if ($keyword && $wechatId) {
            $isAccurate = Yii::app()->request->getParam('isAccurate');
            $keywordArray = explode(',', $keyword);
            foreach ($keywordArray as $k) {
                $keywords = Yii::app()->db->createCommand()
                    ->select('name, isAccurate')
                    ->from('keywords')
                    ->where(array('and', 'wechatId=' . $wechatId, array('like', 'name', array('%' . $k . '%'))))
                    ->queryAll();
                if ($keywords) {
                    foreach ($keywords as $k) {
                        if ($k['name'] == $keyword) {
                            //更新时防止将自身值作为判断了
                            $count++;
                        }
                        switch ($k['isAccurate']) {
                            case 1:
                                //当前关键词精准匹配
                                if ($isAccurate) {
                                    //新加关键字为精准匹配
                                    if ($k['name'] == $keyword) {
                                        $exitKeywords .= $k['name'] . ',';
                                    }
                                } else {
                                    //新加关键字模糊匹配
                                    if (mb_strpos($k['name'], $keyword) !== false) {
                                        $exitKeywords .= $k['name'] . ',';
                                    }
                                }
                                break;
                            case 0:
                                //当前关键词模糊匹配
                                if ($isAccurate) {
                                    //新加关键字为精准匹配
                                    if (mb_strpos($keyword, $k['name']) !== false) {
                                        $exitKeywords .= $k['name'] . ',';
                                    }
                                } else {
                                    //新加关键字模糊匹配
                                    if (mb_strpos($keyword, $k['name']) !== false || mb_strpos($k['name'], $keyword) !== false) {
                                        $exitKeywords .= $k['name'] . ',';
                                    }
                                }
                                break;
                        }
                    }
                    if ($exitKeywords && ($count == 0 || $count > 1)) {
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
}