<?php

/**
 * Created by app.
 * User: druphliu
 * Date: 14-11-4
 * Time: 下午2:36
 */
class Globals
{

    const TYPE_TEXT = 'text';
    const TYPE_GIFT = 'gift';
    const TYPE_SCRATCH = 'scratch';//刮刮乐
	const TYPE_WHEEL = 'wheel';//大转盘
    const TYPE_EGG = 'egg';//彩蛋
    const TYPE_ACTIVE = 'active';//
    const TYPE_REGISTRATION = 'registration';//
    const TYPE_OPEN = 'open';
    const TYPE_IMAGE_TEXT = 'image-text';
    const TYPE_KEYWORDS = 'keywords';
    const TYPE_MENU = 'menu';
    const TYPE_URL = 'url';
    const TYPE_BASE_REPLAY = 'basereplay';
    const REPLAY_TYPE_SUBSCRIBE = 'subscribe';
    const REPLAY_TYPE_DEFAULT = 'default';
    const CODE_TYPE_LEGAL = 1; //正版
    const CODE_TYPE_UNLEGAL = 2;//越狱
    const SETTING_KEY_MENU = 'menu';
    const SETTING_KEY_ACCESS_TOKEN = 'accessToken';
    const SETTING_KEY_JS_TOKEN = 'jsToken';
    const AUTH_KEY = '8ce6340c55bc25374258b0e4fc2d4de4';//加密函数key
    const ACTIVE_AWARD_TYPE_MIX = 1;//混合奖项
    const ACTIVE_AWARD_TYPE_ENTITY = 3;//全实物
    const ACTIVE_AWARD_TYPE_VIRTUAL = 2;//全虚拟
    public static $activeAwardTypeList = array(
        self::ACTIVE_AWARD_TYPE_MIX=>'混合奖项',
        self::ACTIVE_AWARD_TYPE_ENTITY=>'实物奖项',
        self::ACTIVE_AWARD_TYPE_VIRTUAL=>'虚拟奖项'
    );
    public static $codeTypeList = array(
       self::CODE_TYPE_LEGAL=>'正版',
        self::CODE_TYPE_UNLEGAL=>'混版'
    );
    public static $typeList = array(
        self::TYPE_KEYWORDS=>'关键词',
        self::TYPE_URL => 'URL');

    public static $wechatErrorCode = array(
        40001 => '获取access_token时AppSecret错误，或者access_token无效',
        40002 => '不合法的凭证类型',
        40004 => '不合法的媒体文件类型',
        40005 => '不合法的文件类型',
        40006 => '不合法的文件大小',
        40007 => '不合法的媒体文件id',
        40008 => '不合法的消息类型',
        40009 => '不合法的图片文件大小',
        40010 => '不合法的语音文件大小',
        40011 => '不合法的视频文件大小',
        40012 => '不合法的缩略图文件大小',
        40013 => '不合法的APPID',
        40014 => '不合法的access_token',
        40015 => '不合法的菜单类型',
        40016 => '不合法的按钮个数',
        40017 => '不合法的按钮个数',
        40018 => '不合法的按钮名字长度',
        40019 => '不合法的按钮KEY长度',
        40020 => '不合法的按钮URL长度',
        40021 => '不合法的菜单版本号',
        40022 => '不合法的子菜单级数',
        40023 => '不合法的子菜单按钮个数',
        40024 => '不合法的子菜单按钮类型',
        40025 => '不合法的子菜单按钮名字长度',
        40026 => '不合法的子菜单按钮KEY长度',
        40027 => '不合法的子菜单按钮URL长度',
        40028 => '不合法的自定义菜单使用用户',
        40029 => '不合法的oauth_code',
        40030 => '不合法的refresh_token',
        40031 => '不合法的openid列表',
        40032 => '不合法的openid列表长度',
        40033 => '不合法的请求字符，不能包含\uxxxx格式的字符',
        40035 => '不合法的参数',
        40038 => '不合法的请求格式',
        40039 => '不合法的URL长度',
        40050 => '不合法的分组id',
        40051 => '分组名字不合法',
        41001 => '缺少access_token参数',
        41002 => '缺少appid参数',
        41003 => '缺少refresh_token参数',
        41004 => '缺少secret参数',
        41005 => '缺少多媒体文件数据',
        41006 => '缺少media_id参数',
        41007 => '缺少子菜单数据',
        41008 => '缺少oauth code',
        41009 => '缺少openid',
        42001 => 'access_token超时',
        42002 => 'refresh_token超时',
        42003 => 'oauth_code超时',
        43001 => '需要GET请求',
        43002 => '需要POST请求',
        43003 => '需要HTTPS请求',
        43004 => '需要接收者关注',
        43005 => '需要好友关系',
        44001 => '多媒体文件为空',
        44002 => 'POST的数据包为空',
        44003 => '图文消息内容为空',
        44004 => '文本消息内容为空',
        45001 => '多媒体文件大小超过限制',
        45002 => '消息内容超过限制',
        45003 => '标题字段超过限制',
        45004 => '描述字段超过限制',
        45005 => '链接字段超过限制',
        45006 => '图片链接字段超过限制',
        45007 => '语音播放时间超过限制',
        45008 => '图文消息超过限制',
        45009 => '接口调用超过限制',
        45010 => '创建菜单个数超过限制',
        45015 => '回复时间超过限制',
        45016 => '系统分组，不允许修改',
        45017 => '分组名字过长',
        45018 => '分组数量超过上限',
        46001 => '不存在媒体数据',
        46002 => '不存在的菜单版本',
        46003 => '不存在的菜单数据',
        46004 => '不存在的用户',
        47001 => '解析JSON/XML内容错误',
        48001 => 'api功能未授权',
        50001 => '用户未授权该api',
    );
    const WECHAT_RESPONSE_OK = 0;
    const APP_ID = 'wx0c3037e0a311908e';
    const APP_SECRET = 'cb9577372e1f7e13d4a9c648d0c4ba7c';
    const MENU_UPDATE_URL = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token=%s';
    const MENU_DELETE_URL = 'https://api.weixin.qq.com/cgi-bin/menu/delete?access_token=%s';

    /**
     * $string 明文或密文
     * $operation 加密ENCODE或解密DECODE
     * $key 密钥
     * $expiry 密钥有效期
     */
    public static function authcode($string, $operation = 'DECODE', $key = '', $expiry = 0)
    {
        if($operation=='DECODE'){
            $string = strtr($string, '-_', '+/');
        }
        // 动态密匙长度，相同的明文会生成不同密文就是依靠动态密匙
        // 加入随机密钥，可以令密文无任何规律，即便是原文和密钥完全相同，加密结果也会每次不同，增大破解难度。
        // 取值越大，密文变动规律越大，密文变化 = 16 的 $ckey_length 次方
        // 当此值为 0 时，则不产生随机密钥
        $ckey_length = 4;

        // 密匙
        // $GLOBALS['discuz_auth_key'] 这里可以根据自己的需要修改
        $key = md5($key ? $key : self::AUTH_KEY);

        // 密匙a会参与加解密
        $keya = md5(substr($key, 0, 16));
        // 密匙b会用来做数据完整性验证
        $keyb = md5(substr($key, 16, 16));
        // 密匙c用于变化生成的密文
        $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length) : substr(md5(microtime()), -$ckey_length)) : '';
        // 参与运算的密匙
        $cryptkey = $keya . md5($keya . $keyc);
        $key_length = strlen($cryptkey);
        // 明文，前10位用来保存时间戳，解密时验证数据有效性，10到26位用来保存$keyb(密匙b)，解密时会通过这个密匙验证数据完整性
        // 如果是解码的话，会从第$ckey_length位开始，因为密文前$ckey_length位保存 动态密匙，以保证解密正确
        $string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0) . substr(md5($string . $keyb), 0, 16) . $string;
        $string_length = strlen($string);
        $result = '';
        $box = range(0, 255);
        $rndkey = array();
        // 产生密匙簿
        for ($i = 0; $i <= 255; $i++) {
            $rndkey[$i] = ord($cryptkey[$i % $key_length]);
        }
        // 用固定的算法，打乱密匙簿，增加随机性，好像很复杂，实际上并不会增加密文的强度
        for ($j = $i = 0; $i < 256; $i++) {
            $j = ($j + $box[$i] + $rndkey[$i]) % 256;
            $tmp = $box[$i];
            $box[$i] = $box[$j];
            $box[$j] = $tmp;
        }
        // 核心加解密部分
        for ($a = $j = $i = 0; $i < $string_length; $i++) {
            $a = ($a + 1) % 256;
            $j = ($j + $box[$a]) % 256;
            $tmp = $box[$a];
            $box[$a] = $box[$j];
            $box[$j] = $tmp;
            // 从密匙簿得出密匙进行异或，再转成字符
            $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
        }
        if ($operation == 'DECODE') {
            // substr($result, 0, 10) == 0 验证数据有效性
            // substr($result, 0, 10) - time() > 0 验证数据有效性
            // substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16) 验证数据完整性
            // 验证数据有效性，请看未加密明文的格式

            if ((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26) . $keyb), 0, 16)) {
                return substr($result, 26);
            } else {
                return '';
            }
        } else {
            // 把动态密匙保存在密文里，这也是为什么同样的明文，生产不同密文后能解密的原因
            // 因为加密后的密文可能是一些特殊字符，复制过程可能会丢失，所以用base64编码
            return strtr( $keyc . str_replace('=', '', base64_encode($result)), '+/', '-_');
        }
    }

    public static function getToken($wechatId)
    {
        $msg = '参数有误';
        $tokenValue = '';
        $tokenModel = SettingModel::model()->find("wechatId = :wechatId and `key`=:key",
            array(':wechatId' => $wechatId, ':key' => Globals::SETTING_KEY_ACCESS_TOKEN));
        if ($tokenModel) {
            if ((time() - $tokenModel->created_at) < WechatToken::EXPIRES_IN) {
                $tokenValue = $tokenModel->value;
            }
        }
        if (!$tokenValue) {
            $wechat = WechatModel::model()->findByPk($wechatId);
            $appid = $wechat->appid;
            $secret = $wechat->secret;
            $token = WechatToken::getToken($appid, $secret);
            if ($token['status'] == WechatToken::OK) {
                $tokenValue = $token['result'];
                //update token
                if (!$tokenModel) {
                    $tokenModel = new SettingModel();
                    $tokenModel->wechatId = $wechatId;
                    $tokenModel->key = Globals::SETTING_KEY_ACCESS_TOKEN;
                }
                $tokenModel->value = $tokenValue;
                $tokenModel->created_at = time();
                $tokenModel->save();
            } else {
                $msg = $token['result'];
            }
        }
        return array('tokenValue' => $tokenValue, 'msg' => $msg);
    }

    public static function getJsToken($wechatId)
    {
        $msg = '参数有误';
        $tokenValue = '';
        $tokenModel = SettingModel::model()->find("wechatId = :wechatId and `key`=:key",
            array(':wechatId' => $wechatId, ':key' => Globals::SETTING_KEY_JS_TOKEN));
        if ($tokenModel) {
            if ((time() - $tokenModel->created_at) < WechatToken::EXPIRES_IN) {
                $tokenValue = $tokenModel->value;
            }
        }
        if (!$tokenValue) {
            $jsToken = WechatToken::getJsToken($tokenValue);
            if ($jsToken['status'] == WechatToken::OK) {
                $tokenValue = $jsToken['result'];
                //update token
                if (!$tokenModel) {
                    $tokenModel = new SettingModel();
                    $tokenModel->wechatId = $wechatId;
                    $tokenModel->key = Globals::SETTING_KEY_JS_TOKEN;
                }
                $tokenModel->value = $tokenValue;
                $tokenModel->created_at = time();
                $tokenModel->save();
            } else {
                $msg = $jsToken['result'];
            }
        }
        return array('tokenValue' => $tokenValue, 'msg' => $msg);
    }

}