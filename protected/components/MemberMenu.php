<?php

/**
 * Created by PhpStorm.
 * User: druphliu
 * Date: 14-6-20
 * Time: 下午10:40
 */
class MemberMenu extends CWidget
{
    public $menus;
    public static $menuList = array(
        '基础设置' => array('controller' => 'manager', 'act' => 'manager', 'class' => 'fa fa-gears', 'action' => array(
            array('name' => '功能管理', 'url' => 'manager/index', 'act' => 'manager_index', 'list_acl' => array()),
            array('name' => '关注回复', 'url' => 'manager/subscribeReplay', 'act' => 'manager_subscribeReplay', 'list_acl' => array()),
            array('name' => '关键词回复', 'url' => 'manager/keyWords', 'act' => 'manager_keyWords', 'list_acl' => array()),
        )),
        '营销管理' => array('controller' => 'market', 'act' => 'market', 'class' => 'fa fa-bullhorn', 'action' => array(
            array('name' => '礼包领取', 'url' => 'market/gift', 'act' => 'market_gift', 'list_acl' => array()),
        )),
        '会员' => array('controller' => 'member', 'act' => 'member', 'class' => 'icon-user', 'action' => array(
            array('name' => '会员管理', 'url' => 'member/admin', 'act' => 'member_admin', 'list_acl' => array())
        )),
        '日志管理' => array('controller' => 'log', 'act' => 'log', 'class' => 'icon-exclamation-sign', 'action' => array(
            array('name' => '系统日志', 'url' => 'log/index', 'act' => 'log_index', 'list_acl' => array())
        ))
    );
    private static $userMenu = array(
        '公号管理' => array('controller' => 'wechat', 'act' => 'wechat', 'class' => 'fa fa-weixin', 'action' => array(
            array('name' => '账号列表', 'url' => 'wechat/index', 'act' => 'wechat_index', 'list_acl' => array()),
            array('name' => '添加账号', 'url' => 'wechat/add', 'act' => 'wechat_add', 'list_acl' => array()),
        )),
        '个人信息' => array('controller' => 'user', 'act' => 'user', 'class' => 'fa fa-user', 'action' => array(
            array('name' => '修改信息', 'url' => 'user/info', 'act' => 'user_info', 'list_acl' => array()),
            array('name' => '修改密码', 'url' => 'user/pswd', 'act' => 'user_pswd', 'list_acl' => array()),
        )),
        '会员升级' => array('controller' => 'vip', 'act' => 'vip', 'class' => 'fa fa-microphone', 'action' => array(
            array('name' => '充值升级', 'url' => 'vip/lv', 'act' => 'vip_lv', 'list_acl' => array()),
        ))
    );

    public static function GetUserMenu()
    {
        $menuList = array();
        $controllerId = Yii::app()->controller->getid();
        $userAllowAction = array('wechat', 'user', 'vip');
        $mangerAllowAction = array('manager','market');
        if (in_array($controllerId, $userAllowAction)) {
            $menuList = self::$userMenu;
        } else if (in_array($controllerId, $mangerAllowAction)) {
            $userInfo = Yii::app()->session['userInfo'];
            $group = Yii::app()->session['group'];
            $actionArray = explode(',', $group[$userInfo['groupId']]->action);
            $menuList = self::$menuList;
            foreach ($menuList as $k => $m) {
                if (!in_array($m['act'], $actionArray)) {
                    unset($menuList[$k]);
                }
            }
        }
        return $menuList;
    }

    public function run()
    {
        $this->menus = MemberMenu::GetUserMenu();
        $this->render('memberMenu');
    }
} 