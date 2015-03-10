<?php

class ApiController extends Controller
{
    public function actionIndex($id)
    {
        $response = '';
        $originalId = $id;
        $wechatInfo = WechatModel::model()->find('originalId=:originalId', array(':originalId' => $originalId));
        if ($wechatInfo) {
            $wechatApi = new WechatApi($wechatInfo->token);
            switch (Yii::app()->request->requestType) {
                case 'GET':
                    if (Yii::app()->request->getParam('echostr')) {
                        $response = $wechatApi->valid();
                    }
                    break;
                case 'POST':
                    $post_string = $GLOBALS["HTTP_RAW_POST_DATA"];
                    $builder = new WeChatRequestBuilder();
                    $request = $builder->builder($post_string);
                    switch ($request) {
                        case $request instanceof WeChatTextRequest:
                            $response = $this->textResponse($wechatInfo->id, $request);
                            break;
                        case $request instanceof WeChatEventRequest:
                            switch ($request->event_type) {
                                case WeChatEventRequest::$type_subscribe:
                                    $response = $this->subscribeResponse($wechatInfo, $request);
                                    break;
                                case WeChatEventRequest::$type_menu:
                                    //menu response
                                    $key = $request->event_key;
                                    $response = $this->menuResponse($key, $request);
                                    break;
                                case WeChatEventRequest::$type_location:
                                    //TODO 上报地址位置
                                    break;
                                case WeChatEventRequest::$type_scan:
                                    //TODO 扫描二维码
                                    break;
                            }
                            break;
                        case $request instanceof WeChatLocationRequest:
                            //TODO 地理位置消息
                            break;
                    }
                    break;
            }
            echo $response;
        }
    }

    private function textResponse($wechatId, $request)
    {
        $keyword = $legalType = '';
        $message = trim($request->content);
        //刮刮卡预处理
        if (mb_strpos($message, '正版') !== false) {
            $legalType = Globals::CODE_TYPE_LEGAL;
            $message = mb_substr($message, 6);
        } elseif (mb_strpos($message, '混版') !== false) {
            $legalType = Globals::CODE_TYPE_UNLEGAL;
            $message = mb_substr($message, 6);
        }elseif(mb_strpos($message,'中奖查询')){
            $legalType = -1;
            $message = mb_substr($message, 0, -12);
        }
        //find keywords
        $keywords = KeywordsModel::model()->findAll("wechatId=:wechatId and name like concat('%',:name,'%') order by id desc",
            array(':wechatId' => $wechatId, ':name' => $message));
        foreach ($keywords as $k) {
            if ($k->isAccurate and trim($k->name) == $message) {
                //精准匹配
                $keyword = $k;
            }
            if (!$k->isAccurate and mb_strpos($k->name, $message) !== false) {
                //模糊匹配
                $keyword = $k;
            }
        }
        $type = $keyword ? $keyword->type : '';
        $responseId = $keyword->responseId;
        $response = $this->protocol($type, $wechatId, $responseId, $request->from_user_name,$legalType);
        $xml = $response->_to_xml($request);
        return $xml;
    }

    private function subscribeResponse($wechatInfo, $request)
    {
        $response = $this->_baseResponse($wechatInfo, $request, Globals::REPLAY_TYPE_SUBSCRIBE);
        $xml = $response->_to_xml($request);
        return $xml;
    }

    private function menuResponse($key, $request)
    {
        $menuInfo = MenuModel::model()->find('name=:name', array(':name' => $key));
        if ($menuInfo) {
            $keywordsId = $menuInfo->keywordsId;
            switch ($menuInfo->type) {
                case Globals::TYPE_KEYWORDS:
                    $keywordsInfo = KeywordsModel::model()->findByPk($keywordsId);
                    $response = $this->protocol($keywordsInfo->type, $keywordsInfo->wechatId, $keywordsInfo->responseId, $request->from_user_name);
                    break;
            }
        }
        return $response;
    }

    private function protocol($type, $wechatId, $responseId, $openId,$legalType=0)
    {
        switch ($type) {
            case TextReplayModel::TEXT_REPLAY_TYPE:
                $response = $this->_getTextReplay($responseId);
                break;
            case ImagetextreplayModel::IMAGE_TEXT_REPLAY_TYPE:
                $response = $this->_getImageTextReplay($responseId, Globals::TYPE_KEYWORDS);
                break;
            case GiftModel::GIFT_TYPE:
                //礼包领取
                $response = $this->_getGiftReplay($responseId, $openId);
                break;
            case OpenReplayModel::OPEN_TYPE:
                //转接
                $response = $this->_getOpenReplay($responseId);
                return $response;
                break;
            case Globals::TYPE_ACTIVE:
                $response = $this->_getActiveReplay($responseId,$openId,$legalType);
                break;
            case Globals::TYPE_SCRATCH:
                //刮刮乐
                $response = $this->_getScratch($responseId, $openId, $legalType);
                break;
            case Globals::TYPE_WHEEL:
                //刮刮乐
                $response = $this->_getWheel($responseId, $openId, $legalType);
                break;
            case Globals::TYPE_EGG:
                //砸金蛋
                $response = $this->_getEgg($responseId, $openId, $legalType);
                break;
            default:
                $response = $this->_baseResponse($wechatId, $openId, Globals::REPLAY_TYPE_DEFAULT);
                break;
        }
        return $response;
    }

    private function _baseResponse($wechatInfo, $openId, $replayType)
    {
        $subscribeInfo = BasereplayModel::model()->find('wechatId=:wechatId and replayType=:replayType', array(':wechatId' => $wechatInfo->id, ':replayType' => $replayType));
        $type = $subscribeInfo ? $subscribeInfo->type : TextReplayModel::TEXT_REPLAY_TYPE;
        switch ($type) {
            case TextReplayModel::TEXT_REPLAY_TYPE:
                if ($subscribeInfo) {
                    $response = $this->_getTextReplay($subscribeInfo->responseId, $openId);
                } else {
                    $content = $replayType == Globals::REPLAY_TYPE_SUBSCRIBE ? '感谢您关注' . $wechatInfo->name : '暂时不理解你说的';
                    $response = new WeChatTextResponse($content);
                }
                break;
            case ImagetextreplayModel::IMAGE_TEXT_REPLAY_TYPE:
                $response = $this->_getImageTextReplay($subscribeInfo->responseId, Globals::TYPE_SUBSCRIBE);
                break;
        }
        return $response;
    }


    /**
     * 文本回复
     * @param $responseId
     * @return WeChatTextResponse
     */
    private function _getTextReplay($responseId, $openId = '')
    {
        $responseInfo = TextReplayModel::model()->findByPk($responseId);
        $content = $responseInfo->content;
        $content = str_replace('fromUsername', $openId, $content);
        $responseObj = new WeChatTextResponse(str_replace(array('<br>', '</br>'), chr(13), $content));
        return $responseObj;
    }

    /**
     * 图文回复
     * @param $responseId
     * @return WeChatArticleResponse
     */
    private function _getImageTextReplay($responseId, $type)
    {
        $responseInfo = ImagetextreplayModel::model()->findByPk($responseId);
        $responseObj = new WeChatArticleResponse();
        $responseObj->add_article($responseInfo->title, $responseInfo->description, $responseInfo->imgUrl, $responseInfo->url);
        $list = ImagetextreplayModel::model()->findAll('type=:type and parentId=:parentId', array(':type' => $type, ':parentId' => $responseId));
        if ($list) {
            foreach ($list as $article) {
                $responseObj->add_article($article->title, $article->description, $article->imgUrl, $article->url);
            }
        }
        return $responseObj;
    }

    private function _getGiftReplay($responseId, $openId)
    {
        $giftInfo = GiftModel::model()->findByPk($responseId);
        if ($giftInfo->startTime > date('Y-m-d H:i:s')) {
            $content = $giftInfo->unstartMsg ? $giftInfo->unstartMsg : "抱歉,还未开始呢";
        } elseif ($giftInfo->endTime < date('Y-m-d H:i:s')) {
            $content = $giftInfo->endMsg ? $giftInfo->endMsg : "抱歉,你来晚了";
        } elseif ($giftInfo->status == 0) {
            $content = $giftInfo->pauseMsg ? $giftInfo->pauseMsg : "抱歉,活动暂时停止";
        } else {
            $codeTableName = GiftModel::model()->getCodeTableName($giftInfo->wechatId);
            $userHasGet = GiftCodeModel::model($codeTableName)->find('giftId=:giftId and openId=:openId', array(':giftId' => $giftInfo->id, ':openId' => $openId));
            if ($userHasGet) {
                $content = $giftInfo->RTemplate ? str_replace('{code}', $userHasGet->code, $giftInfo->RTemplate) : $userHasGet->code;
            } else {
                $codeInfo = GiftCodeModel::model($codeTableName)->find('giftId=:giftId and openId is null', array(':giftId' => $giftInfo->id));
                if ($codeInfo) {
                    //update
                    $codeInfo->openId = $openId;
                    $codeInfo->save();
                    $content = $giftInfo->template ? str_replace('{code}', $codeInfo->code, $giftInfo->template) : $codeInfo->code;
                } else {
                    $content = $giftInfo->codeOverMsg ? $giftInfo->codeOverMsg : "抱歉,领完了";
                }
            }
        }
        $responseObj = new WeChatTextResponse($content);
        return $responseObj;
    }

    private function _getOpenReplay($responseId)
    {
        $post_string = $GLOBALS["HTTP_RAW_POST_DATA"];
        $openReplayInfo = OpenReplayModel::model()->with('open_openPlatForm')->findByPk($responseId);
        $apiUrl = $openReplayInfo->open_openPlatForm->apiUrl;
        $token = $openReplayInfo->open_openPlatForm->token;
        $wechatApi = new WechatApi($token);
        $url = $wechatApi->buildSignUrl($apiUrl);
        $result = HttpRequest::sendHttpRequest($url, $post_string, 'POST', array("Content-type: text/xml"));
        return $result['content'] ? $result['content'] : '';
    }

    private function _getActiveReplay($responseId, $openId, $type){
        $active = ActiveModel::model()->findByPk($responseId);
        switch($active->type){
            case Globals::TYPE_REGISTRATION:
                    $responseObj = $this->_getRegistration($responseId, $openId, $type);
                break;
        }
        return $responseObj;
    }

    private function _getRegistration($responseId, $openId, $type){
        $active = ActiveModel::model()->findByPk($responseId);
        if (!$type) {
            $keywords = KeywordsModel::model()->find('type=:type and responseId=:responseId',
                array(':type' => Globals::TYPE_WHEEL, ':responseId' => $responseId));
            $content = '参与' . $active->title . '请回复:正版(混版)' . $keywords->name.'参与活动。回复:'.$keywords->name.'中奖查询,查询中奖信息';
            $responseObj = new WeChatTextResponse($content);
            return $responseObj;
        }
        if($type==-1){
            $content = $this->_getActiveAwards($openId,$responseId,Globals::TYPE_REGISTRATION);
        }else{
            if ($active->startTime > date('Y-m-d H:i:s')) {
                $content = $active->unstartMsg ? $active->unstartMsg : "抱歉,还未开始呢";
            } elseif ($active->endTime < date('Y-m-d H:i:s')) {
                $content = $active->endMsg ? $active->endMsg : "抱歉,你来晚了";
            } elseif ($active->status == 0) {
                $content = $active->pauseMsg ? $active->pauseMsg : "抱歉,活动暂时停止";
            } else {
                $string = $openId . '|' . $responseId . '|' . $type;
                $code = Globals::authcode($string, 'ENCODE');
                $url = Yii::app()->params['siteUrl'] . Yii::app()->createUrl('registration/handle', array('code' => $code));
                $responseObj = new WeChatArticleResponse();
                $responseObj->add_article($active->title, '', Yii::app()->params['siteUrl'] . '/wechat/upload/market/registration/active.jpg', $url);
            }
        }
        $responseObj = isset($responseObj) ? $responseObj : new WeChatTextResponse($content);
        return $responseObj;
    }

    private function _getScratch($responseId, $openId, $type)
    {
        $disable = 1;
        $logTable = 'scratch_log';
        Yii::import('application.modules.scratch.models.ScratchModel');
        Yii::import('application.modules.scratch.models.ScratchLogModel');
        $scratch = ScratchModel::model()->findByPk($responseId);
        if (!$type) {
            $keywords = KeywordsModel::model()->find('type=:type and responseId=:responseId',
                array(':type' => Globals::TYPE_SCRATCH, ':responseId' => $responseId));
            $content = '参与' . $scratch->title . '请回复:正版(混版)' . $keywords->name;
            $responseObj = new WeChatTextResponse($content);
            return $responseObj;
        }
        //查看刮卡次数
        $totalCount = $scratch->times;
        if ($totalCount == -1) {//本活动只能参与一次
            $count = ScratchLogModel::model($logTable)->count('openId=:openId and scratchId=:scratchId',
                array(':openId' => $openId, ':scratchId' => $scratch->id));
            if ($count > 0)
                $disable = 0;
        }

        if ($totalCount > 0) {
            $start = strtotime(date('Y-m-d')) - 1;
            $end = strtotime(date('Y-m-d', strtotime('1 days'))) - 1;
            $count = ScratchLogModel::model($logTable)->count('openId=:openId and scratchId=:scratchId and datetime>:start and datetime<:end',
                array(':openId' => $openId, ':scratchId' => $scratch->id, ':start' => $start, ':end' => $end));
            if ($count >= $totalCount)
                $disable = 0;
        }
        if ($disable == 0) {
            $content = $totalCount == -1 ? '你本次活动的参与次数已完' : '今天的刮奖次数已完，明天再来吧';
            $responseObj = new WeChatTextResponse($content);
            return $responseObj;
        }
        if ($scratch->startTime > date('Y-m-d H:i:s')) {
            $content = $scratch->unstartMsg ? $scratch->unstartMsg : "抱歉,还未开始呢";
        } elseif ($scratch->endTime < date('Y-m-d H:i:s')) {
            $content = $scratch->endMsg ? $scratch->endMsg : "抱歉,你来晚了";
        } elseif ($scratch->status == 0) {
            $content = $scratch->pauseMsg ? $scratch->pauseMsg : "抱歉,活动暂时停止";
        } else {
            $string = $openId . '|' . $responseId . '|' . $type;
            $code = Globals::authcode($string, 'ENCODE');
            $url = Yii::app()->params['siteUrl'] . Yii::app()->createUrl('scratch/handle', array('code' => $code));
            $responseObj = new WeChatArticleResponse();
            $responseObj->add_article($scratch->title, '', Yii::app()->params['siteUrl'] . '/wechat/' . Yii::app()->params['scratchPath'] . '/' . $scratch->wechatId . '/' . $scratch->backgroundPic, $url);
        }
        $responseObj = isset($responseObj) ? $responseObj : new WeChatTextResponse($content);
        return $responseObj;
    }

    private function _getWheel($responseId, $openId, $type)
    {
        $disable = 1;
        $logTable = 'wheel_log';
        Yii::import('application.modules.wheel.models.WheelModel');
        Yii::import('application.modules.wheel.models.WheelLogModel');
        $wheel = WheelModel::model()->findByPk($responseId);
        if (!$type) {
            $keywords = KeywordsModel::model()->find('type=:type and responseId=:responseId',
                array(':type' => Globals::TYPE_WHEEL, ':responseId' => $responseId));
            $content = '参与' . $wheel->title . '请回复:正版(混版)' . $keywords->name;
            $responseObj = new WeChatTextResponse($content);
            return $responseObj;
        }
        //查看刮卡次数
        $totalCount = $wheel->times;
        if ($totalCount == -1) {//本活动只能参与一次
            $count = WheelLogModel::model($logTable)->count('openId=:openId and wheelId=:wheelId',
                array(':openId' => $openId, ':wheelId' => $wheel->id));
            if ($count > 0)
                $disable = 0;
        }

        if ($totalCount > 0) {
            $start = strtotime(date('Y-m-d')) - 1;
            $end = strtotime(date('Y-m-d', strtotime('1 days'))) - 1;
            $count = WheelLogModel::model($logTable)->count('openId=:openId and wheelId=:wheelId and datetime>:start and datetime<:end',
                array(':openId' => $openId, ':wheelId' => $wheel->id, ':start' => $start, ':end' => $end));
            if ($count >= $totalCount)
                $disable = 0;
        }
        if ($disable == 0) {
            $content = $totalCount == -1 ? '你本次活动的参与次数已完' : '今天的转盘次数已完，明天再来吧';
            $responseObj = new WeChatTextResponse($content);
            return $responseObj;
        }
        if ($wheel->startTime > date('Y-m-d H:i:s')) {
            $content = $wheel->unstartMsg ? $wheel->unstartMsg : "抱歉,还未开始呢";
        } elseif ($wheel->endTime < date('Y-m-d H:i:s')) {
            $content = $wheel->endMsg ? $wheel->endMsg : "抱歉,你来晚了";
        } elseif ($wheel->status == 0) {
            $content = $wheel->pauseMsg ? $wheel->pauseMsg : "抱歉,活动暂时停止";
        } else {
            $string = $openId . '|' . $responseId . '|' . $type;
            $code = Globals::authcode($string, 'ENCODE');
            $url = Yii::app()->params['siteUrl'] . Yii::app()->createUrl('wheel/handle', array('code' => $code));
            $responseObj = new WeChatArticleResponse();
            $responseObj->add_article($wheel->title, '', Yii::app()->params['siteUrl'] . '/wechat/' . Yii::app()->params['wheelPath'] . '/' . $wheel->wechatId . '/' . $wheel->backgroundPic, $url);
        }
        $responseObj = isset($responseObj) ? $responseObj : new WeChatTextResponse($content);
        return $responseObj;
    }


    private function _getEgg($responseId, $openId, $type)
    {
        $disable = 1;
        $logTable = 'active_log';
        $active = ActiveModel::model()->findByPk($responseId);
        if (!$type) {
            $keywords = KeywordsModel::model()->find('type=:type and responseId=:responseId',
                array(':type' => Globals::TYPE_EGG, ':responseId' => $responseId));
            $content = '参与' . $active->title . '请回复:正版(混版)' . $keywords->name;
            $responseObj = new WeChatTextResponse($content);
            return $responseObj;
        }
        //查看刮卡次数
        $totalCount = $active->times;
        if ($totalCount == -1) {//本活动只能参与一次
            $count = ActiveLogModel::model($logTable)->count('openId=:openId and activeId=:activeId',
                array(':openId' => $openId, ':activeId' => $active->id));
            if ($count > 0)
                $disable = 0;
        }

        if ($totalCount > 0) {
            $start = strtotime(date('Y-m-d')) - 1;
            $end = strtotime(date('Y-m-d', strtotime('1 days'))) - 1;
            $count = ActiveLogModel::model($logTable)->count('openId=:openId and activeId=:activeId and datetime>:start and datetime<:end',
                array(':openId' => $openId, ':activeId' => $active->id, ':start' => $start, ':end' => $end));
            if ($count >= $totalCount)
                $disable = 0;
        }
        if ($disable == 0) {
            $content = $totalCount == -1 ? '你本次活动的参与次数已完' : '今天的转盘次数已完，明天再来吧';
            $responseObj = new WeChatTextResponse($content);
            return $responseObj;
        }
        if ($active->startTime > date('Y-m-d H:i:s')) {
            $content = $active->unstartMsg ? $active->unstartMsg : "抱歉,还未开始呢";
        } elseif ($active->endTime < date('Y-m-d H:i:s')) {
            $content = $active->endMsg ? $active->endMsg : "抱歉,你来晚了";
        } elseif ($active->status == 0) {
            $content = $active->pauseMsg ? $active->pauseMsg : "抱歉,活动暂时停止";
        } else {
            $string = $openId . '|' . $responseId . '|' . $type;
            $code = Globals::authcode($string, 'ENCODE');
            $url = Yii::app()->params['siteUrl'] . Yii::app()->createUrl('egg/handle', array('code' => $code));
            $responseObj = new WeChatArticleResponse();
            $responseObj->add_article($active->title, '', '', $url);
        }
        $responseObj = isset($responseObj) ? $responseObj : new WeChatTextResponse($content);
        return $responseObj;
    }

    /**
     * 活动中奖查询
     * @param $openId
     * @param $wechatId
     * @param $type
     * @return string
     */
    private function _getActiveAwards($openId,$activeId,$type){
        $table = 'active_awards';
        $content = '';
        $awardsList = ActiveAwardsModel::model($table)->findAll('activeId=:activeId and openId=:openId',
            array(':activeId'=>$activeId,':openId'=>$openId));
        foreach($awardsList as $a){
            switch($type){
                case Globals::TYPE_REGISTRATION:
                    $content .= '签到'.$a->grade.'礼包:'.$a->code."\n";
                    break;
                default:
                    $content .=$a->grade.'等奖礼包:'.$a->code."\n";
                        break;
            }
        }
        return $content ? $content : '暂无中奖信息';
    }
    // Uncomment the following methods and override them if needed
    /*
    public function filters()
    {
        // return the filter configuration for this controller, e.g.:
        return array(
            'inlineFilterName',
            array(
                'class'=>'path.to.FilterClass',
                'propertyName'=>'propertyValue',
            ),
        );
    }

    public function actions()
    {
        // return external action classes, e.g.:
        return array(
            'action1'=>'path.to.ActionClass',
            'action2'=>array(
                'class'=>'path.to.AnotherActionClass',
                'propertyName'=>'propertyValue',
            ),
        );
    }
    */
}
