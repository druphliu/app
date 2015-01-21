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
        $logTable = 'active_log';
        $table = 'active_awards';
        $remainCount = $prize = $hasCount = 0;
        $code = Yii::app()->request->getParam('code');
        list($openId, $activeId, $type) = explode('|', Globals::authcode($code, 'DECODE'));
        $active = ActiveModel::model()->findByPk($activeId);
        //活动是否开始
        if ($active->startTime > date('Y-m-d H:i:s')) {
            $prize = 1;
        } elseif ($active->endTime < date('Y-m-d H:i:s')) {
            $prize = 1;
        } elseif ($active->status == 0) {
            $prize = 1;
        }
        $totalCount = $active->times;
        //次数限制
        if ($totalCount == -1) {//本活动只能参与一次
            $count = ActiveLogModel::model($logTable)->count('openId=:openId and activeId=:activeId',
                array(':openId' => $openId, ':activeId' => $activeId));
            $totalCount = 1;
            if ($count > 0)
                $hasCount = $count;
        }
        if ($totalCount > 0) {
            $start = strtotime(date('Y-m-d')) - 1;
            $end = strtotime(date('Y-m-d', strtotime('1 days'))) - 1;
            $count = ActiveLogModel::model($logTable)->count('openId=:openId and activeId=:activeId and datetime>:start and datetime<:end',
                array(':openId' => $openId, ':activeId' => $activeId, ':start' => $start, ':end' => $end));
            $hasCount = $count;
        }
        $this->renderPartial('active', array('active' => $active, 'prize' => $prize, 'hasCount' => $hasCount,
            'encryption' => $code, 'totalCount' => $totalCount));
    }

    public function actionActive()
    {
        $probability = 0;
        $sn = '';
        $rand = rand(1, 100000);
        $table = 'active_awards';
        $logTable = 'active_log';
        $encryption = Yii::app()->request->getParam('encryption');
        list($openId, $activeId, $type) = explode('|', Globals::authcode($encryption, 'DECODE'));
        $wheel = ActiveModel::model()->findByPk($activeId);
        $awards = unserialize($wheel->awards);
        //查看当前用户是否已中奖
        $wheelInfo = ActiveAwardsModel::model($table)->find('activeId=:activeId and openId=:openId and status<>0',
            array(':activeId' => $activeId, ':openId' => $openId));
        if (!$wheelInfo) {
            foreach ($awards as $k => $v) {
                $probability += $v['probability'] * 1000;
                $award[$k] = array('name' => $v['name'], 'num' => $probability);
            }
            //奖品不足时，发送激活码，
            if ($probability < 100000) {
                $award[] = array('name' => '谢谢参与', 'num' => 100000);
            }
            //返回给客户端的奖品名称
            foreach ($award as $key => $val) {
                if (isset($award[$key - 1])) {
                    //if ($rand > $award[$key - 1]['num'] && $rand <= $val['num']) {
                        if ($rand > $award[$key - 1]['num']) {
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
                $awardCount = ActiveLogModel::model($table)->count('grade=:grade and activeId=:activeId and status<>0',
                    array(':grade' => $return['grade'], ':activeId' => $activeId)); //已中奖个数
                if (($awardCount <= 0 || $awardCount < $awards[$return['grade']]['count'])) {//未超过系统设置中奖个数
                    if ($awards[$return['grade']]['isentity'] == 1) {

                        $awardModel = new ActiveAwardsModel($table);
                        $awardModel->openId = $openId;
                        $awardModel->activeId = $activeId;
                        $awardModel->grade = $return['grade'];
                        $awardModel->code = $return['name'];
                        $awardModel->isentity = isset($awards[$return['grade']]['isentity']) ? $awards[$return['grade']]['isentity'] : 0;
                        $awardModel->status = 0;
                        $awardModel->type = $type;
                        $awardModel->datetime = time();
                        $awardModel->save();


                    } else {
                        //码类
                        $awardData = ActiveAwardsModel::model($table)->find('activeId=:activeId and grade=:grade and type=:type and status=0',
                            array(':activeId' => $activeId, ':grade' => $return['grade'], ':type' => $type));
                        if ($awardData) {//是否还有码
                            $sn = $awardData->code;
                            $awardData->status = 1;
                            $awardData->openId = $openId;
                            $awardData->datetime = time();
                            $awardData->save();
                        } else {

                            $return = array('grade' => 0, 'name' => '谢谢参与', 'num' => 100000);
                        }
                    }

                }
            }
        } else {
            $return = array('grade' => 0, 'name' => '谢谢参与', 'num' => 100000);
        }
        //log
        $log = new ActiveLogModel($logTable);
        $log->datetime = time();
        $log->openId = $openId;
        $log->activeId = $activeId;
        //$log->save();
        $encryption = Globals::authcode($openId . '|' . $return['grade'] . '|' . $activeId, 'ENCODE');
        echo json_encode(array('success' => $return['grade'] > 0 ? 1 : 0, 'prizetype' => $return['grade'], 'sn' => $sn, 'name' => $return['name'], 'encryption' => $encryption));
    }

    public function actionSave()
    {//status 更新为2
        $success = false;
        $table = 'wheel_awards';
        $area = $_POST['area'];
        $role = $_POST['role'];
        $banben = $_POST['banben'];
        $msg = '中奖信息失效或系统异常';
        $encryption = $_POST['encryption'];
        list($openid, $grade, $wheelId) = explode('|', Globals::authcode($encryption, 'DECODE'));
        $wheelInfo = WheelModel::model()->findByPk($wheelId);
        if ($wheelInfo) {
            $awards = unserialize($wheelInfo->awards);
            if ($awards[$grade]) {
                $success = true;
                $msg = '你的信息已收录，我们会及时联系你';
                //存储用户信息
                $codeInfo = WheelAwardsModel::model($table)->find('openId=:openId and wheelId=:wheelId and grade=:grade',
                    array(':openId' => $openid, ':wheelId' => $wheelId, ':grade' => $grade));
                $codeInfo->status = 2;
                $codeInfo->area = $area;
                $codeInfo->role = $role;
                $codeInfo->banben = $banben;
                $codeInfo->save();
            }
        }
        echo json_encode(array('success' => $success, 'msg' => $msg));
    }

}
