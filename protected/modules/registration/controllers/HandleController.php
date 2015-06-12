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
        $code = Yii::app()->request->getParam('code');
        $prize = 1;
        list($openId, $activeId, $type) = explode('|', Globals::authcode($code, 'DECODE'));//type 越狱或正版
        $active = ActiveModel::model()->findByPk($activeId);
        $logTable = ActiveLogModel::model()->getTableName($active->wechatId);
        $startDate = $active->startTime;
        $endDate = $active->endTime;
        //活动是否开始
        if ( $startDate> date('Y-m-d H:i:s')) {
            $prize = 1;
        } elseif ($endDate < date('Y-m-d H:i:s')) {
            $prize = 1;
        } elseif ($active->status == 0) {
            $prize = 1;
        }
        $prize = $active->status == 0 ? 0 : $prize;
        $connection = Yii::app()->db;
        $sql = "SELECT FROM_UNIXTIME(datetime,'%m%d') as date FROM $logTable where activeId=$activeId and openId='$openId'";
        $command = $connection->createCommand($sql);
        $dataReader = $command->query();
        while (($row = $dataReader->read()) !== false) {
            $logList[$row['date']] = $row['date'];
        }
        $MouthStart = strtotime(date('Y-m',strtotime($startDate)).'-01');
        $days = (strtotime(date('Y-m-d',strtotime("$startDate +1 month -1 day")))-$MouthStart)/86400;
        for($i=1;$i<=$days+1;$i++){
            $time = strtotime(date('Y-m',$MouthStart).'-'.$i);
            $key = date('md',$time);
            $isSin = isset($logList[$key]) ? 1 : 0;
            $date[$key] = array('m'=>date('m',$time),'d'=>date('d',$time),'isSin'=>$isSin);
        }
        $weekNum = date('w',$MouthStart);
        if($weekNum>0){
            for($i=1;$i<=$weekNum;$i++){
                array_unshift($date,array('m'=>0,'d'=>0,'isSin'=>0));
            }
        }
        $wechatInfo = WechatModel::model()->findByPk($active->wechatId);
        $this->renderPartial('active', array('active' => $active, 'prize' => $prize, 'encryption' => $code,
            'appId'=>$wechatInfo->appid,'date'=>$date));
    }

    public function actionActive()
    {
        $awards = array();
        $result = -1;
        $prize = 1;
        $msg = '活动已结束';
        $sinDate = 0;
        $encryption = Yii::app()->request->getParam('encryption');
        list($openId, $activeId, $type) = explode('|', Globals::authcode($encryption, 'DECODE'));
        $active = ActiveModel::model()->findByPk($activeId);
        $table = ActiveAwardsModel::model()->getTableName($active->wechatId);
        $logTable = ActiveLogModel::model()->getTableName($active->wechatId);
        //活动是否开始
        if ($active->startTime > date('Y-m-d H:i:s')) {
            $prize = 1;
        } elseif ($active->endTime < date('Y-m-d H:i:s')) {
            $prize = 1;
        } elseif ($active->status == 0) {
            $prize = 1;
        }
        $prize = $active->status == 0 ? 0 : $prize;
        if ($prize == 1) {
            //检查今天是否已经签到
            $startTime  = strtotime(date('Y-m-d'));
            $endTime = strtotime(date('Y-m-d', strtotime('+1 days')))-1;
            $logExit = ActiveLogModel::model($logTable)->find('activeId=:activeId and openId=:openId and
            datetime>=' . $startTime . ' and datetime<=' . $endTime, array(':activeId' => $activeId, ':openId' => $openId));
            $count = ActiveLogModel::model($logTable)->count('activeId=:activeId and openId=:openId',
                array(':activeId' => $activeId, ':openId' => $openId));//已经签到次数
            $grade = $count+1;//当天签到天数
            $codeHas = ActiveAwardsModel::model($table)->find('activeId=:activeId and grade=:grade and
                        type=:type and openId=:openId', array(':activeId' => $activeId, ':grade' => $grade,':type'=>$type,':openId'=>$openId));
            if ($logExit || $codeHas) {
                $result = 1;//签到过了
                $msg = '你今天已经签过到了';
            } else {

                $awardsArray = unserialize($active->awards);
                foreach($awardsArray as $a){
                    $awards[$a['count']] = $a;
                }
                if(isset($awards[$grade])){
                    //获取礼包码
                    $code = ActiveAwardsModel::model($table)->find('activeId=:activeId and grade=:grade and
                        type=:type and openId is null', array(':activeId' => $activeId, ':grade' => $grade,':type'=>$type));
                    if ($code) {
                        $code->status=2;
                        $code->openId = $openId;
                        $code->datetime = time();
                        $code->save();
                        $result = 2;

                        $msg = '恭喜你，获得' . $awards[$grade]['name'] . '，礼包码为:'.$code->code;
                    } else {
                        $result = -3;
                        $msg = '抱歉，礼包码发完了，请联系客服';
                    }
                }else{
                        $result = 3;
                        $msg = '恭喜你，签到成功';
                    }
                $sinDate = date('md');
                //log
                $log = new ActiveLogModel($logTable);
                $log->datetime = time();
                $log->openId = $openId;
                $log->activeId = $activeId;
                $log->save();
                }

        }
        echo json_encode(array('success' => $result, 'msg' => $msg,'sinDate'=>$sinDate));
    }


}
