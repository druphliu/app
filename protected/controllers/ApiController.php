<?php

class ApiController extends Controller
{
    private $wechatInfo;

    public function beforeAction($action)
    {
        $originalId = Yii::app()->request->getParam('id');
        if ($originalId) {
            $this->wechatInfo = WechatModel::model()->find('originalId=:originalId', array(':originalId' => $originalId));
        }
        if (!$this->wechatInfo)
            return;
        return parent::beforeAction($action);
    }

    public function actionIndex()
    {
        $response = '';

        $wechatApi = new WechatApi($this->wechatInfo->token);
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
                        $response = $this->textResponse($request);
                        break;
                    case $request instanceof WeChatEventRequest:
                        switch ($request->event_type) {
                            case WeChatEventRequest::$type_subscribe:
                                $response = $this->subscribeResponse($request);
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

    private function textResponse($request)
    {
        $wechatId = $this->wechatInfo->id;
        $keyword = $legalType = '';
        $message = trim($request->content);
        //刮刮卡预处理
        if (mb_strpos($message, '正版') !== false) {
            $legalType = Globals::CODE_TYPE_LEGAL;
            $message = mb_substr($message, 6);
        } elseif (mb_strpos($message, '混版') !== false) {
            $legalType = Globals::CODE_TYPE_UNLEGAL;
            $message = mb_substr($message, 6);
        } elseif (mb_strpos($message, '中奖查询')) {
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
        $responseId = $keyword ? $keyword->responseId : '';
        $response = $this->protocol($type, $responseId, $request->from_user_name, $legalType);
        $xml = $response ? $response->_to_xml($request) : '';
        return $xml;
    }

    private function subscribeResponse($request)
    {
        $response = $this->_baseResponse($request->from_user_name, Globals::REPLAY_TYPE_SUBSCRIBE);
        $xml = $request ? $response->_to_xml($request) : '';
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
                    $response = $this->protocol($keywordsInfo->type, $keywordsInfo->responseId, $request->from_user_name);
                    break;
            }
        }
	$xml = $response ? $response->_to_xml($request) : '';
        return $xml;
    }

    private function protocol($type, $responseId, $openId, $legalType = 0)
    {
        switch ($type) {
            case TextReplayModel::TEXT_REPLAY_TYPE:
                $response = $this->_getTextReplay($responseId);
                break;
            case ImagetextreplayModel::IMAGE_TEXT_REPLAY_TYPE:
                $response = $this->_getImageTextReplay($responseId, Globals::TYPE_KEYWORDS);
                break;
            case Globals::TYPE_GIFT:
                //礼包领取
                $response = $this->_getGiftReplay($responseId, $openId);
                break;
            case Globals::TYPE_OPEN:
                //转接
                $response = $this->_getOpenReplay($responseId);
                die($response);
                break;
            case Globals::TYPE_ACTIVE:
                $response = $this->_getActiveReplay($responseId, $openId, $legalType);
                break;
            default:
                $response = $this->_baseResponse($openId, Globals::REPLAY_TYPE_DEFAULT);
                break;
        }
        return $response;
    }

    private function _baseResponse($openId, $replayType)
    {
        $subscribeInfo = BasereplayModel::model()->find('wechatId=:wechatId and replayType=:replayType', array(':wechatId' => $this->wechatInfo->id, ':replayType' => $replayType));
        $type = $subscribeInfo ? $subscribeInfo->type : TextReplayModel::TEXT_REPLAY_TYPE;
        switch ($type) {
            case TextReplayModel::TEXT_REPLAY_TYPE:
                if ($subscribeInfo) {
                    $response = $this->_getTextReplay($subscribeInfo->responseId, $openId);
                } else {
                    $content = $replayType == Globals::REPLAY_TYPE_SUBSCRIBE ? '感谢您关注' . $this->wechatInfo->name : '';
                    $response = $content ? new WeChatTextResponse($content) : '';
                }
                break;
            case ImagetextreplayModel::IMAGE_TEXT_REPLAY_TYPE:
                $response = $this->_getImageTextReplay($subscribeInfo->responseId, Globals::REPLAY_TYPE_SUBSCRIBE);
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
        $responseObj = strip_tags($responseInfo->content) ? new WeChatTextResponse(str_replace(array('<br>', '</br>'), chr(13), $content)) : '';
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

    private function _getActiveReplay($responseId, $openId, $type)
    {
        $active = ActiveModel::model()->findByPk($responseId);
        if ($type !== 0)
            $responseObj = $this->_getActive($responseId, $openId, $type, $active->type);
        else
            $responseObj = $this->_getActiveAwards($openId, $active->id, $active->type);
        return $responseObj;
    }

    /**
     * 活动响应
     * @param $responseId 活动ID
     * @param $openId
     * @param $type 正混版类型,默认不区分
     * @param $activeType 活动类型(礼包码，刮刮乐。。。)
     * @return WeChatArticleResponse|WeChatTextResponse
     */
    private function _getActive($responseId, $openId, $type=0, $activeType)
    {
        $active = ActiveModel::model()->findByPk($responseId);
        $disable = 1;
        if (!$type && $active->isSensitive) {
            $keywords = KeywordsModel::model()->find('type=:type and responseId=:responseId',
                array(':type' => Globals::TYPE_ACTIVE, ':responseId' => $responseId));
            $content = '参与' . $active->title . '请回复:正版(混版)' . $keywords->name;
            $responseObj = new WeChatTextResponse($content);
            return $responseObj;
        }
        //查看次数
        $totalCount = $active->times;
        $logTable = ActiveLogModel::model()->getTableName($active->wechatId);
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
            $content = $totalCount == -1 ? '你本次活动的参与次数已完' : '今天的已参加完，明天再来吧';
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
            $url = Yii::app()->params['siteUrl'] . Yii::app()->createUrl($activeType . '/handle', array('code' => $code));
            $responseObj = new WeChatArticleResponse();
            $picUrl = $active->focusImg ? $active->focusImg : 'assets/images/' . $active->type . '.jpg';
            $responseObj->add_article($active->title, $active->desc, Yii::app()->params['siteUrl'] . '/' . $picUrl, $url);
        }
        $responseObj = isset($responseObj) ? $responseObj : new WeChatTextResponse($content);
        return $responseObj;
    }

    /**
     * 活动中奖查询
     * @param $openId
     * @param $type
     * @return string
     */
    private function _getActiveAwards($openId, $activeId, $type)
    {
        $table = ActiveAwardsModel::model()->getTableName($this->wechatInfo->id);
        $content = '';
        $awardsList = ActiveAwardsModel::model($table)->findAll('activeId=:activeId and openId=:openId',
            array(':activeId' => $activeId, ':openId' => $openId));
        foreach ($awardsList as $a) {
            switch ($type) {
                case Globals::TYPE_REGISTRATION:
                    $content .= '签到' . $a->grade . '礼包:' . $a->code . "\n";
                    break;
                default:
                    $content .= $a->grade . '等奖礼包:' . $a->code . "\n";
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
