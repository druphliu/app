<?php

/**
 * Created by PhpStorm.
 * User: druphliu
 * Date: 14-2-9
 * Time: 下午2:53
 */
class WechatToken
{

    const  EXPIRES_IN = 7200;
    const OK = 1;
    const FAILED = -1;
    const WECHAT_TOKEN = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=%s&secret=%s';
    const WECHAT_JS_TOKEN = 'https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=%s&type=jsapi';


    public static function getToken($appid,$appsecret)
    {
        $url = sprintf(self::WECHAT_TOKEN, $appid, $appsecret);
        $result = HttpRequest::sendHttpRequest($url);
        $data = json_decode($result['content']);
        $result = isset($data->access_token) ? $data->access_token : Globals::$wechatErrorCode[$data->errcode];
        $status = isset($data->access_token) ? self::OK : self::FAILED;
        return array('status' => $status, 'result' => $result);
    }

    public static function getJsToken($token)
    {
        $url = sprintf(self::WECHAT_JS_TOKEN, $token);
        $result = HttpRequest::sendHttpRequest($url);
        $data = json_decode($result['content']);
        $result = isset($data->ticket) ? $data->ticket : Globals::$wechatErrorCode[$data->errcode];
        $status = isset($data->ticket) ? self::OK : self::FAILED;
        return array('status' => $status, 'result' => $result);
    }
}