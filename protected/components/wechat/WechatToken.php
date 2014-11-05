<?php

/**
 * Created by PhpStorm.
 * User: druphliu
 * Date: 14-2-9
 * Time: 下午2:53
 */
class WechatToken
{
    private $appid, $appsecret, $url;
    const  EXPIRES_IN = 7200;
    const OK = 1;
    const FAILED = -1;

    function __construct($appid, $appsecret)
    {
        $this->appid = $appid;
        $this->appsecret = $appsecret;
        $this->url = sprintf("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=%s&secret=%s", $this->appid, $this->appsecret);
    }

    function getToken()
    {
        $result = HttpRequest::sendHttpRequest($this->url);
        $data = json_decode($result['content']);
        $result = isset($data->access_token) ? $data->access_token : GlobalParams::$wechatErrorCode[$data->errcode];
        $status = isset($data->access_token) ? self::OK : self::FAILED;
        return array('status' => $status, 'result' => $result);
    }
}