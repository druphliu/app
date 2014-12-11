<?php

/**
 * Created by PhpStorm.
 * User: druphliu
 * Date: 2014/12/9
 * Time: 16:27
 */
class HandleController extends CController
{
    public function actionIndex()
    {
        $table = 'scratch_awards';
        $probability = 0;
        $return = $encryption = '';
        $rand = rand(1, 100000);
        $code = Yii::app()->request->getParam('code');
        list($openId, $scratchId, $type) = explode('|', Globals::authcode($code, 'DECODE'));
        $scratch = ScratchModel::model()->findByPk($scratchId);
        $button = Yii::app()->params['siteUrl'].'/'.Yii::app()->params['scratchPath'].'/'.$scratch->wechatId.'/'.$scratch->button;
        $awards = unserialize($scratch->awards);
        $return['grade'] = -1;
        $return['name'] = '谢谢参与';
        //查看当前用户是否已中奖
        $awardInfo = ScratchAwardsModel::model($table)->find('scratchId=:scratchId and openId=:openId and status<>0',
            array(':scratchId' => $scratchId, ':openId' => $openId));
        if (!$awardInfo) {
            foreach ($awards as $k => $v) {
                $probability += $v['probability'] * 1000;
                $award[$k] = array('name' => $v['name'], 'num' => $probability);
            }
            //奖品不足时，发送激活码，
            if ($probability < 100000) {
                $award[] = array('name' => '礼包码', 'num' => 100000);
            }
            //返回给客户端的奖品名称
            foreach ($award as $key => $val) {
                if (isset($award[$key - 1])) {
                    if ($rand > $award[$key - 1]['num'] && $rand <= $val['num']) {
                        //if ($rand > $award[$key - 1]['num']) {
                        $return = $val;
                        $return['grade'] = $key;
                        break;
                    }
                } else {
                    if ($rand > 0 && $rand <= $val['num']) {
                        $return = $val;
                        $return['grade'] = $key;
                        break;
                    }
                }
            }
            if ($return['grade'] > 0) {
                $awardCount = ScratchAwardsModel::model($table)->count('grade=:grade and scratchId=:scratchId and status<>0',
                    array(':grade' => $return['grade'], ':scratchId' => $scratchId)); //已中奖个数
                if ($awardCount <= 0 || $awardCount < $awards[$return['grade']]['count']) {//未超过系统设置中奖个数
                    $awardModel = new ScratchAwardsModel($table);
                    $awardModel->openId = $openId;
                    $awardModel->scratchId = $scratchId;
                    $awardModel->grade = $return['grade'];
                    $awardModel->code = $return['name'];
                    $awardModel->isentity = isset($awards[$return['grade']]['isentity']) ? $awards[$return['grade']]['isentity'] : 0;
                    $awardModel->status = 0;
                    $awardModel->save();
                } else {
                    //奖品达到系统设置个数

                    //查看是否已经赠送了礼包
                    $code = ScratchAwardsModel::model($table)->find('grade=:grade and scratchId=:scratchId and openId=:openId and status<>0',
                        array(':grade' => 0, ':scratchId' => $scratchId, ':openId' => $openId));
                    if (!$code) {
                        //取一条礼包码
                        $code = ScratchAwardsModel::model($table)->find('grade=:grade and scratchId=:scratchId and type=:type and status=:status',
                            array(':grade' => 0, ':scratchId' => $scratchId, ':type' => $type, ':status' => 0));
                        if ($code) {
                            $code->openId = $openId;
                            $code->time = time();
                            $code->save();
                            $return['grade'] = 0;
                            $return['name'] = $code->code;//礼包码
                        }
                    }

                }
            } else {
                //礼包奖品，查询是否已经获得过礼包
                $code = ScratchAwardsModel::model($table)->find('grade=:grade and scratchId=:scratchId and openId=:openId and status<>:status',
                    array(':grade' => 0, ':scratchId' => $scratchId, ':openId' => $openId, ':status' => 0));
                if (!$code) {
                    //取一条礼包码
                    $code = ScratchAwardsModel::model($table)->find('grade=:grade and scratchId=:scratchId and type=:type and status=:status',
                        array(':grade' => 0, ':scratchId' => $scratchId, ':type' => $type, ':status' => 0));
                    if ($code) {
                        $code->openId = $openId;
                        $code->datetime = time();
                        $code->save();
                        $return['grade'] = 0;
                        $return['name'] = $code->code;//礼包码
                    }
                } else {
                    $return['grade'] = -1;
                    $return['name'] = '谢谢参与';
                }
            }
        }
        $encryption = Globals::authcode($openId . '|' . $return['grade'] . '|' . $scratchId, 'ENCODE');
        $this->renderPartial('active', array('scratch' => $scratch, 'prize' => $return, 'encryption' => $encryption,'button'=>$button));
    }

    public function actionConfirm()
    {//status 更新为1,表明用户已经参
        $status = false;
        $encryption = $_POST['encryption'];
        $table = 'scratch_awards';
        list($openid, $grade, $scratchId) = explode('|', Globals::authcode($encryption, 'DECODE'));
        $code = ScratchAwardsModel::model($table)->find('grade=:grade and scratchId=:scratchId and openId=:openId',
            array(':grade' => $grade, ':scratchId' => $scratchId, ':openId' => $openid));
        if ($code) {
            $code->status = $grade == 0 ? 2 : 1;
            $code->save();
            $status = true;
        }
        echo json_encode(array('status' => $status));
    }

    public function actionSave()
    {//status 更新为2
        $success = false;
        $table = 'scratch_awards';
        $tel = $_POST['tel'];
        $msg = '中奖信息失效或系统异常';
        $encryption = $_POST['encryption'];
        $name = $_POST['code'];
        list($openid, $grade, $scratchId) = explode('|', Globals::authcode($encryption, 'DECODE'));
        $scratchInfo = ScratchModel::model()->findByPk($scratchId);
        if ($scratchInfo && $tel) {
            $awards = unserialize($scratchInfo->awards);
            if ($awards[$grade] && $awards[$grade]['name'] == $name) {
                $success = true;
                $msg = '你的信息已收录，我们会及时联系你';
                //存储用户信息
                $codeInfo = ScratchAwardsModel::model($table)->find('openId=:openId and scratchId=:scratchId and grade=:grade',
                    array(':openId' => $openid, ':scratchId' => $scratchId, ':grade' => $grade));
                $codeInfo->status = 2;
                $codeInfo->telphone = $tel;
                $codeInfo->save();
            }
        }
        echo json_encode(array('success' => $success, 'msg' => $msg));
    }

}