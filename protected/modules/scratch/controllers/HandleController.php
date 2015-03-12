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
        $disable = 1;
        $probability = $remainCount = 0;
        $return = $encryption = '';
        $rand = rand(1, 100000);
        $code = Yii::app()->request->getParam('code');
        list($openId, $activeId, $type) = explode('|', Globals::authcode($code, 'DECODE'));
        $active = ActiveModel::model()->findByPk($activeId);
        //活动是否开始
        if ($active->startTime > date('Y-m-d H:i:s')) {
            $disable = false;
        } elseif ($active->endTime < date('Y-m-d H:i:s')) {
            $disable = false;
        } elseif ($active->status == 0) {
            $disable = false;
        }
        $totalCount = $active->times;
        $button = '';
        $awards = unserialize($active->awards);
        $return['grade'] = -1;
        $return['name'] = '谢谢参与';
        //次数限制
        if ($totalCount == -1) {//本活动只能参与一次
            $count = ActiveLogModel::model($logTable)->count('openId=:openId and activeId=:activeId',
                array(':openId' => $openId, ':activeId' => $activeId));
            if ($count > 0)
                $disable = 0;
        }

        if ($totalCount > 0) {
            $start = strtotime(date('Y-m-d')) - 1;
            $end = strtotime(date('Y-m-d',strtotime('1 days')))-1;
            $count = ActiveLogModel::model($logTable)->count('openId=:openId and activeId=:activeId and datetime>:start and datetime<:end',
                array(':openId' => $openId, ':activeId' => $activeId, ':start' => $start, ':end' => $end));
            if ($count >= $totalCount)
                $disable = 0;
            else
                $remainCount = $totalCount - $count;
        }

        //查看当前用户是否已中奖
        $awardInfo = ActiveAwardsModel::model($table)->find('activeId=:activeId and openId=:openId and status<>0',
            array(':activeId' => $activeId, ':openId' => $openId));
        if (!$awardInfo && $disable) {
            foreach ($awards as $k => $v) {
                $probability += 1 * 1000;
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
                $awardCount = ActiveAwardsModel::model($table)->count('grade=:grade and activeId=:activeId and status<>0',
                    array(':grade' => $return['grade'], ':activeId' => $activeId)); //已中奖个数
                if ($awardCount <= 0 || $awardCount < $awards[$return['grade']]['count']) {//未超过系统设置中奖个数
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
                    //奖品达到系统设置个数

                    //查看是否已经赠送了礼包
                    $code = ActiveAwardsModel::model($table)->find('grade=:grade and activeId=:activeId and openId=:openId and status<>0',
                        array(':grade' => 0, ':activeId' => $activeId, ':openId' => $openId));
                    if (!$code) {
                        //取一条礼包码
                        $code = ActiveAwardsModel::model($table)->find('grade=:grade and activeId=:activeId and type=:type and status=:status',
                            array(':grade' => 0, ':activeId' => $activeId, ':type' => $type, ':status' => 0));
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
                $code = ActiveAwardsModel::model($table)->find('grade=:grade and activeId=:activeId and openId=:openId and status<>:status',
                    array(':grade' => 0, ':activeId' => $activeId, ':openId' => $openId, ':status' => 0));
                if (!$code) {
                    //取一条礼包码
                    $code = ActiveAwardsModel::model($table)->find('grade=:grade and activeId=:activeId and type=:type and status=:status',
                        array(':grade' => 0, ':activeId' => $activeId, ':type' => $type, ':status' => 0));
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
        $encryption = Globals::authcode($openId . '|' . $return['grade'] . '|' . $activeId, 'ENCODE');
        $this->renderPartial('active', array('active' => $active, 'prize' => $return, 'encryption' => $encryption,
            'button' => $button, 'disable' => $disable, 'remainCount' => $remainCount, 'totalCount' => $totalCount));
    }

    public function actionConfirm()
    {//status 更新为1,表明用户已经参
        $logTable = 'scratch_log';
        $status = false;
        $encryption = $_POST['encryption'];
        $table = 'scratch_awards';
        list($openid, $grade, $activeId) = explode('|', Globals::authcode($encryption, 'DECODE'));
        $code = ScratchAwardsModel::model($table)->find('grade=:grade and activeId=:activeId and openId=:openId',
            array(':grade' => $grade, ':activeId' => $activeId, ':openId' => $openid));
        if ($code) {
            $code->status = $grade == 0 ? 2 : 1;
            $code->save();
            $status = true;
        }
        $log = new ScratchLogModel($logTable);
        $log->datetime = time();
        $log->openId = $openid;
        $log->activeId = $activeId;
        $log->save();
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
        list($openid, $grade, $activeId) = explode('|', Globals::authcode($encryption, 'DECODE'));
        $scratchInfo = ScratchModel::model()->findByPk($activeId);
        if ($scratchInfo && $tel) {
            $awards = unserialize($scratchInfo->awards);
            if ($awards[$grade] && $awards[$grade]['name'] == $name) {
                $success = true;
                $msg = '你的信息已收录，我们会及时联系你';
                //存储用户信息
                $codeInfo = ScratchAwardsModel::model($table)->find('openId=:openId and activeId=:activeId and grade=:grade',
                    array(':openId' => $openid, ':activeId' => $activeId, ':grade' => $grade));
                $codeInfo->status = 2;
                $codeInfo->telphone = $tel;
                $codeInfo->save();
            }
        }
        echo json_encode(array('success' => $success, 'msg' => $msg));
    }

}
