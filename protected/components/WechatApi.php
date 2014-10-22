<?php

/**
 * Created by app.
 * User: druphliu
 * Date: 14-10-13
 * Time: 下午12:40
 */
class WechatApi
{
    private $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function valid()
    {
        $response = false;
        $echoStr = $_GET["echostr"];

        //valid signature , option
        if ($this->checkSignature()) {
            $response = $echoStr;
        }
        return $response;
    }

    public function buildSignUrl($apiUrl, $params=array())
    {
        $url = $apiUrl;
        $token = $this->token;
        $timestamp = "13123123";
        $nonce = 'asdad';
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr);
        $tmpStr = implode($tmpArr);
        $signature = sha1($tmpStr);
        $data = array_merge(array('signature' => $signature, 'timestamp' => $timestamp, 'nonce' => $nonce), $params);
        if (strpos($url, '?')) $url .= '&' . http_build_query($data);
        else $url .= '?' . http_build_query($data);
        return $url;
    }

    private function checkSignature()
    {
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];

        $tmpArr = array($this->token, $timestamp, $nonce);
        sort($tmpArr);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);
        if ($tmpStr == $signature) {
            return true;
        } else {
            return false;
        }
    }
} 