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
        $keyword = '';
        $message = trim($request->content);
        //find keywords
        $keywords = KeywordsModel::model()->findAll("wechatId=:wechatId and name like concat('%',:name,'%')",
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
        $type = $keyword ? $keyword->type : TextreplayModel::TEXT_REPLAY_TYPE;
        switch ($type) {
            case TextreplayModel::TEXT_REPLAY_TYPE:
                if ($keyword) {
                    $response = $this->_getTextReplay($keyword->responseId);
                } else {
                    $content = "亲，暂时不能理解你说的";
                    $response = new WeChatTextResponse($content);
                }
                break;
            case ImagetextreplayModel::IMAGE_TEXT_REPLAY_TYPE:
                $response = $this->_getImageTextReplay($keyword->responseId);
                break;
            case GiftModel::GIFT_TYPE:
                //礼包领取
                $response = $this->_getGiftReplay($keyword->responseId, $request->from_user_name);
                break;
            case OpenReplayModel::OPEN_TYPE:
                //转接
                $response = $this->_getOpenReplay($keyword->responseId);
                return $response;
                break;
        }
        $xml = $response->_to_xml($request);
        return $xml;
    }

    private function subscribeResponse($wechatInfo, $request)
    {
        $subscribeInfo = SubscribereplayModel::model()->find('wechatId=:wechatId', array(':wechatId' => $wechatInfo->id));
        $type = $subscribeInfo ? $subscribeInfo->type : TextreplayModel::TEXT_REPLAY_TYPE;
        switch ($type) {
            case TextreplayModel::TEXT_REPLAY_TYPE:
                if ($subscribeInfo) {
                    $response = $this->_getTextReplay($subscribeInfo->responseId);
                } else {
                    $content = '感谢您关注' . $wechatInfo->name;
                    $response = new WeChatTextResponse($content);
                }
                break;
            case ImagetextreplayModel::IMAGE_TEXT_REPLAY_TYPE:
                $response = $this->_getImageTextReplay($subscribeInfo->responseId);
                break;
        }
        $xml = $response->_to_xml($request);
        return $xml;
    }

    private function menuResponse($key, $request)
    {
        $menuInfo = MenuactionModel::model()->find('action=:action', array(':action' => $key));
        if ($menuInfo) {
            $responseId = $menuInfo->responseId;
            switch ($menuInfo->type) {
                case TextreplayModel::TEXT_REPLAY_TYPE:
                    $response = $this->_getTextReplay($responseId);
                    break;
                case ImagetextreplayModel::IMAGE_TEXT_REPLAY_TYPE:
                    $response = $this->_getImageTextReplay($responseId);
                    break;
                case GiftModel::GIFT_TYPE:
                    //礼包领取
                    $response = $this->_getGiftReplay($responseId, $request->from_user_name);
                    break;
                case OpenReplayModel::OPEN_TYPE:
                    //转接
                    $response = $this->_getOpenReplay($responseId);
                    return $response;
                    break;
            }
        }
        $xml = $response->_to_xml($request);
        return $xml;
    }

    /**
     * 文本回复
     * @param $responseId
     * @return WeChatTextResponse
     */
    private function _getTextReplay($responseId)
    {
        $responseInfo = TextreplayModel::model()->findByPk($responseId);
        $content = $responseInfo->content;
        $responseObj = new WeChatTextResponse($content);
        return $responseObj;
    }

    /**
     * 图文回复
     * @param $responseId
     * @return WeChatArticleResponse
     */
    private function _getImageTextReplay($responseId)
    {
        $responseInfo = ImagetextreplayModel::model()->findByPk($responseId);
        $responseObj = new WeChatArticleResponse();
        $responseObj->add_article($responseInfo->title, $responseInfo->description, $responseInfo->imgUrl, $responseInfo->url);
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
            $userHasGet = GiftCodeModel::model()->find('giftId=:giftId and openId=:openId', array(':giftId' => $giftInfo->id, ':openId' => $openId));
            if ($userHasGet) {
                $content = $giftInfo->RTemplate ? str_replace('{code}', $userHasGet->code, $giftInfo->RTemplate) : $userHasGet->code;
            } else {
                $codeInfo = GiftCodeModel::model()->find('giftId=:giftId and openId is null', array(':giftId' => $giftInfo->id));
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