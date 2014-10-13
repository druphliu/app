<?php

/**
 * Created by PhpStorm.
 * User: druphliu
 * Date: 14-2-9
 * Time: 下午2:53
 */
class TokenWechat
{
    private $appid, $appsecret, $url;
    private $expires_in = 7200;
    private $DB_KEY = 'token';
    private $DB_TABLE_NAME = 'wechatprofile';

    function __construct($appid, $appsecret)
    {
        $this->appid = $appid;
        $this->appsecret = $appsecret;
        $this->url = sprintf("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=%s&secret=%s", $this->appid, $this->appsecret);
    }

    function get_token()
    {
        $sqlite = new SQLite(DBFILE);
        $sql = "select value from $this->DB_TABLE_NAME where key = '$this->DB_KEY'";
        $sqlite->query($sql);
        $result = $sqlite->fetch();
        $token_arr = unserialize($result['value']);
        if ($token_arr && (time() - $token_arr['expires_at']) < $this->expires_in) {
            $token = $token_arr['access_token'];
            if (!$token) {
                $this->update_token();
            }
        } else {
            $token = $this->update_token();
        }
        return $token;
    }

    private function update_token()
    {
        $sqlite = new SQLite(DBFILE);
        $curl = new curl();
        $curl->setOpt(CURLOPT_RETURNTRANSFER, TRUE);
        $curl->setOpt(CURLOPT_SSL_VERIFYPEER, FALSE);
        $curl->get($this->url);
        $response = $curl->response;
        $json = json_decode($response);
        $access_token = $json->access_token;
        $token_value = serialize(array('access_token' => $access_token, 'expires_at' => time()));
        $sql = "insert into $this->DB_TABLE_NAME values ('$this->DB_KEY', '$token_value')";
        $sqlite->query($sql);
        return $access_token;
    }
}