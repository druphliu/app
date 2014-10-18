<?php

/**
 * Created by app.
 * User: druphliu
 * Date: 14-10-15
 * Time: 下午5:25
 */
class AjaxController extends Controller
{
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
                    ->select('name, isAccurate,type, responseId' )
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

    public function actionGiftStatus($id)
    {
        $status = Yii::app()->request->getParam('status');
        $model = GiftModel::model()->findByPk($id);
        $model->status = in_array($status, array(0, 1)) ? $status : 0;
        $model->save();
        echo json_encode(array('result' => 0));
    }
}