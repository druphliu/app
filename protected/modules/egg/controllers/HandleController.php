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
        $prize = 1;//活动是否开始
        $remainCount = 0;
        $code = Yii::app()->request->getParam('code');
        list($openId, $activeId, $type) = explode('|', Globals::authcode($code, 'DECODE'));
        $active = ActiveModel::model()->findByPk($activeId);
        //活动是否开始
        if ($active->status == 0 || $active->startTime > date('Y-m-d H:i:s') || $active->endTime < date('Y-m-d H:i:s'))
            $prize = 0;
        if ($prize) {
            $totalCount = $active->times;
            //次数限制
            if ($totalCount > 0) {
                $start = strtotime(date('Y-m-d')) - 1;
                $end = strtotime(date('Y-m-d', strtotime('1 days'))) - 1;
                $count = ActiveLogModel::model($logTable)->count('openId=:openId and activeId=:activeId and datetime>:start and datetime<:end',
                    array(':openId' => $openId, ':activeId' => $activeId, ':start' => $start, ':end' => $end));
                if ($count >= $totalCount)
                    $remainCount = 0;
                else
                    $remainCount = $totalCount - $count;
            } elseif ($totalCount < 0) {
                $totalCount = abs($totalCount);
                $count = ActiveLogModel::model($logTable)->count('openId=:openId and activeId=:activeId',
                    array(':openId' => $openId, ':activeId' => $activeId));
                if ($count >= $totalCount) {
                    $remainCount = 0;
                } else {
                    $remainCount = $totalCount - $count;
                }
            } else {
                $remainCount = Globals::XXXX;
            }
        }
        $this->renderPartial('active', array('active' => $active, 'prize' => $prize, 'remainCount' => $remainCount,
            'encryption' => $code, 'totalCount' => $totalCount));
    }

    public function actionActive()
    {
        $snCode = '';
        $prize = 1;//活动是否开始
        $probability = $remainCount = $totalCount = $isentity = $gradeNum = $awardId = 0;
        $grade = -1;
        $rand = rand(1, 100000);
        $table = 'active_awards';
        $logTable = 'active_log';
        $encryption = Yii::app()->request->getParam('encryption');
        list($openId, $activeId, $type) = explode('|', Globals::authcode($encryption, 'DECODE'));
        $active = ActiveModel::model()->findByPk($activeId);
        //活动是否开始
        if ($active->status == 0 || $active->startTime > date('Y-m-d H:i:s') || $active->endTime < date('Y-m-d H:i:s'))
            $prize = 0;
        if ($prize) {
            $awards = unserialize($active->awards);
            $gradeNum = count($awards);
            $totalCount = $active->times;
            //次数限制
            if ($totalCount > 0) {
                $start = strtotime(date('Y-m-d')) - 1;
                $end = strtotime(date('Y-m-d', strtotime('1 days'))) - 1;
                $count = ActiveLogModel::model($logTable)->count('openId=:openId and activeId=:activeId and datetime>:start and datetime<:end',
                    array(':openId' => $openId, ':activeId' => $activeId, ':start' => $start, ':end' => $end));
                if ($count >= $totalCount)
                    $remainCount = 0;
                else
                    $remainCount = $totalCount - $count;
            } elseif ($totalCount < 0) {
                $totalCount = abs($totalCount);
                $count = ActiveLogModel::model($logTable)->count('openId=:openId and activeId=:activeId',
                    array(':openId' => $openId, ':activeId' => $activeId));
                if ($count >= $totalCount) {
                    $remainCount = 0;
                } else {
                    $remainCount = $totalCount - $count;
                }
            } else {
                $remainCount = Globals::XXXX;
            }
            //查看当前用户是否已中奖
            $awardInfo = ActiveAwardsModel::model($table)->find('activeId=:activeId and openId=:openId and status<>0',
                array(':activeId' => $activeId, ':openId' => $openId));

            if (!$awardInfo && $remainCount) {

                foreach ($awards as $k => $v) {
                    $prob = round($v['count'] / $active->predictCount, 2);
                    $probability += $prob * 100000;
                    $award[$k] = array('name' => $v['name'], 'num' => $probability);
                }
                //返回给客户端的奖品名称
                foreach ($award as $key => $val) {
                    if (isset($award[$key - 1])) {
                        if ($rand > $award[$key - 1]['num'] && $rand <= $val['num']) {
                            //if ($rand > $award[$key - 1]['num']) {
                            //   $return = $val;
                            $grade = $key;
                            break;
                        }
                    } else {
                        if ($rand > 0 && $rand <= $val['num']) {
                            // $return = $val;
                            $grade = $key;
                            break;
                        }
                    }
                }
                if ($grade > 0) {
                    $awardCount = ActiveAwardsModel::model($table)->count('grade=:grade and activeId=:activeId and status<>0',
                        array(':grade' => $grade, ':activeId' => $activeId)); //已中奖个数
                    if ($awardCount < $awards[$grade]['count']) {//未超过系统设置中奖个数
                        //是否实物奖
                        if ($awards[$grade]['isentity']) {
                            $isentity = 1;
                            //实物插入中奖记录
                            $awardModel = new ActiveAwardsModel($table);
                            $awardModel->openId = $openId;
                            $awardModel->activeId = $activeId;
                            $awardModel->grade = $grade;
                            $awardModel->code = $awards[$grade]['name'];
                            $awardModel->isentity = 1;
                            $awardModel->status = 1;
                            $awardModel->type = $type;
                            $awardModel->datetime = time();
                            $awardModel->save();
                            $awardId = $awardModel->id;
                        } else {
                            //虚拟 取一条礼包码
                            $onAward = ActiveAwardsModel::model()->find('type=:type and grade=:grade and activeId=:activeId and openId is null',
                                array(':type' => $type, ':grade' => $grade, ':activeId' => $activeId));
                            if ($onAward) {
                                $onAward->openId = $openId;
                                $onAward->status = 2;
                                $onAward->datetime = time();
                                $onAward->save();
                                $snCode = $onAward->code;
                                $awardId = $onAward->id;
                            } else {
                                //礼包码没了....
                                $grade = -1;
                                #todo 报警.......
                            }
                        }
                    } else {
                        //奖品达到系统设置个数,获取参与奖
                        $grade = -1;
                    }
                } else {
                    //获取参与奖
                    $grade = -1;
                }
                $result['success'] = true;
            } elseif ($remainCount <= 0) {
                $result ['error'] = 'invalid';
            }
            if($grade==-1){
                //参与奖
                $participationAward = $this->_getParticipationAward($active, $openId, $type);
                $grade = $participationAward['grade'];
                $snCode = $participationAward['name'];
                $awardId = $participationAward['awardId'];
            }
            //log
            $log = new ActiveLogModel($logTable);
            $log->datetime = time();
            $log->openId = $openId;
            $log->activeId = $activeId;
            $log->save();
            $encryption = Globals::authcode($openId . '|' . $grade . '|' . $activeId . '|' . $type . '|' . $awardId, 'ENCODE');
        }
        $grades = array('超级礼包', '一', '二', '三', '四', '五', '六', '七', '八', '九', '十');
        if ($grade >= 0) {
            $result['gradeName'] = $grades[$grade] . '等奖';
            $result['gradeContent'] = $grade > 0 ? $awards[$grade]['name'] : '';
            $result['snCode'] = $grade == 0 || !$awards[$grade]['isentity'] ? $snCode : $awards[$grade]['name'];
            $result['success'] =1;
        } else {
            $result['success'] = 0;
        }
        $result['grade'] = $grade;
        $result['remainCount'] = $remainCount;
        $result['totalCount'] = $totalCount;
        $result['encryption'] = $encryption;
        $result['isentity'] = $isentity;
        echo json_encode($result);
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

    private function _getParticipationAward($active, $openId, $type)
    {
        $table = 'active_awards';
        $activeId = $active->id;
        $return['awardId'] = 0;
        $return['grade'] = -1;
        $return['name'] = '谢谢参与';
        if ($active->ispaward) {
            //开启了参与奖
            //查看是否已经赠送了礼包
            $code = ActiveAwardsModel::model($table)->find('grade=:grade and activeId=:activeId and openId=:openId and status<>0',
                array(':grade' => 0, ':activeId' => $activeId, ':openId' => $openId));
            if (!$code) {
                //取一条礼包码
                $code = ActiveAwardsModel::model($table)->find('grade=:grade and activeId=:activeId and type=:type and status=:status',
                    array(':grade' => 0, ':activeId' => $activeId, ':type' => $type, ':status' => 0));
                if ($code) {
                    $code->openId = $openId;
                    $code->datetime = time();
                    $code->status = 1;
                    $code->save();
                    $return['awardId'] = $code->id;
                    $return['grade'] = 0;
                    $return['name'] = $code->code;//礼包码
                }
            }
        }
        return $return;
    }

}
