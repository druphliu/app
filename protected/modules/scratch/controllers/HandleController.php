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
        $return['grade'] = -1;
        $return['name'] = '谢谢参与';
        $probability = $remainCount = $totalCount = $dayLimit = 0;
        $isStop = 1;
        $rand = rand(1, 100000);
        $code = Yii::app()->request->getParam('code');
        list($openId, $activeId, $type) = explode('|', Globals::authcode($code, 'DECODE'));
        $active = ActiveModel::model()->findByPk($activeId);
        $logTable = ActiveLogModel::model()->getTableName($active->wechatId);
        $table = ActiveAwardsModel::model()->getTableName($active->wechatId);
        //活动是否开始
        if ($active->status == 0 || $active->startTime > date('Y-m-d H:i:s') || $active->endTime < date('Y-m-d H:i:s'))
            $isStop = 0;
        if($isStop==1){
            $totalCount = $active->times;
            $awards = unserialize($active->awards);
            //次数限制
            if($totalCount > 0) {
                $start = strtotime(date('Y-m-d')) - 1;
                $end = strtotime(date('Y-m-d',strtotime('1 days')))-1;
                $count = ActiveLogModel::model($logTable)->count('openId=:openId and activeId=:activeId and datetime>:start and datetime<:end',
                    array(':openId' => $openId, ':activeId' => $activeId, ':start' => $start, ':end' => $end));
                if ($count >= $totalCount)
                    $remainCount = 0;
                else
                    $remainCount = $totalCount - $count;
            }elseif($totalCount<0){
                $dayLimit = 1;
                $totalCount = abs($totalCount);
                $count = ActiveLogModel::model($logTable)->count('openId=:openId and activeId=:activeId',
                    array(':openId' => $openId, ':activeId' => $activeId));
                if ($count >= $totalCount){
                    $remainCount = 0;
                }else{
                    $remainCount = $totalCount - $count;
                }
            }else{
                $remainCount = Globals::XXXX;
            }

            //查看当前用户是否已中奖
            $awardInfo = ActiveAwardsModel::model($table)->find('activeId=:activeId and openId=:openId and status<>0 and grade>0',
                array(':activeId' => $activeId, ':openId' => $openId));
            if (!$awardInfo && $remainCount) {
                foreach ($awards as $k => $v) {
                    $prob = round($v['count']/$active->predictCount,2);
                    $probability +=  $prob* 100000;
                    $award[$k] = array('name' => $v['name'], 'num' => $probability);
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
                    if ($awardCount < $awards[$return['grade']]['count']) {//未超过系统设置中奖个数
                        //实物将
                        if($awards[$return['grade']]['isentity']){
                            $return['isentity'] = 1;
                            $awardModel = new ActiveAwardsModel($table);
                            $awardModel->openId = $openId;
                            $awardModel->activeId = $activeId;
                            $awardModel->grade = $return['grade'];
                            $awardModel->code = $return['name'];
                            $awardModel->isentity = 1;
                            $awardModel->status = 0;
                            $awardModel->type = $type;
                            $awardModel->datetime = time();
                            $awardModel->save();
                        }else{
                        //虚拟 取code
                            $awardModel = ActiveAwardsModel::model($table)->find('grade=:grade and activeId=:activeId and type=:type and openId is null',
                                array(':grade'=>$return['grade'],':activeId'=>$activeId,':type'=>$type));
                            if($awardModel){
                                $awardModel->openId = $openId;
                                $awardModel->datetime = time();
                                $awardModel->save();
                                $return['snCode'] = $awardModel->code;
                                $return['isentity'] = 0;
                            }else{
                                $return['grade']=-1;
                            }
                        }
                    } else {
                        $return['grade']=-1;
                    }
                } else {
                    //获取参与奖
                    $return['grade']=-1;
                }
            }
            if($return['grade']==-1){
                $participationAward = $this->_getParticipationAward($active,$openId,$type);
                if($participationAward)
                    $return = $participationAward;
            }
        }
        $grades = array(1=>'一',2=>'二',3=>'三',4=>'四',5=>'五',6=>'六',7=>'七',8=>'八',9=>'九','10'=>'十');
        $encryption = Globals::authcode($openId . '|' . $return['grade'] . '|' . $activeId, 'ENCODE');
        $this->renderPartial('active', array('active' => $active, 'prize' => $return, 'encryption' => $encryption,
              'remainCount' => $remainCount, 'totalCount' => $totalCount,'awards'=>unserialize($active->awards),
        'grades'=>$grades,'isStop'=>$isStop,'dayLimit'=>$dayLimit));
    }

    public function actionConfirm()
    {//status 更新为1,表明用户已经参
        $status = false;
        $encryption = $_POST['encryption'];
        $table = 'active_awards';
        list($openid, $grade, $activeId) = explode('|', Globals::authcode($encryption, 'DECODE'));
        $active = ActiveModel::model()->findByPk($activeId);
        $logTable = ActiveLogModel::model()->getTableName($active->wechatId);
        $code = ActiveAwardsModel::model($table)->find('grade=:grade and activeId=:activeId and openId=:openId',
            array(':grade' => $grade, ':activeId' => $activeId, ':openId' => $openid));
        if ($code) {
            $code->status = $grade == 0 ? 2 : 1;
            $code->save();
            $status = true;
        }
        $log = new ActiveLogModel($logTable);
        $log->datetime = time();
        $log->openId = $openid;
        $log->activeId = $activeId;
        $log->save();
        echo json_encode(array('status' => $status));
    }

    public function actionSave()
    {//status 更新为2
        $success = false;
        $tel = $_POST['tel'];
        $msg = '中奖信息失效或系统异常';
        $encryption = $_POST['encryption'];
        $name = $_POST['code'];
        list($openid, $grade, $activeId) = explode('|', Globals::authcode($encryption, 'DECODE'));
        $active = ActiveModel::model()->findByPk($activeId);
        $awardTable = ActiveAwardsModel::model()->getTableName($active->wechatId);
        $awardsInfoTable = ActiveAwardsInfoModel::model()->getTableName($active->wechatId);
        $scratchInfo = ActiveModel::model()->findByPk($activeId);
        $award = ActiveAwardsModel::model($awardTable)->find('openId=:openId and activeId=:activeId and grade=:grade',
            array(':openId' => $openid, ':activeId' => $activeId, ':grade' => $grade));
        if ($scratchInfo && $tel && $award) {
            $awards = unserialize($scratchInfo->awards);
            if ($awards[$grade] && $awards[$grade]['name'] == $name) {
                $success = true;
                $msg = '你的信息已收录，我们会及时联系你';
                $award->status = 2;
                $award->save();
                //存储用户信息
                $awardsInfo = ActiveAwardsInfoModel::model($awardsInfoTable)->find('awardId=:awardId and type=:type',
                    array(':awardId' => $award->id,':type'=>$award->type));
                $awardsInfo = $awardsInfo ? $awardsInfo : new ActiveAwardsInfoModel($awardsInfoTable);
                $awardsInfo->tel = $tel;
                $awardsInfo->awardId = $award->id;
                $awardsInfo->type = $award->type;
                $awardsInfo->save();
            }
        }
        echo json_encode(array('success' => $success, 'msg' => $msg));
    }

    private function _getParticipationAward($active,$openId,$type){
        $table = ActiveAwardsModel::model()->getTableName($active->wechatId);
        $activeId = $active->id;
        $return['grade'] = -1;
        $return['isentity'] = 0;
        $return['name'] = '谢谢参与';
        if($active->ispaward){
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
                    $code->save();
                    $return['grade'] = 0;
                    $return['name'] = $code->code;//礼包码
                }
            }
        }
        return $return;
    }
}
