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
        '设置' => array('controller' => 'setting', 'act' => 'setting', 'class' => 'icon-dashboard', 'action' => array(
            array('name' => '系统设置', 'url' => 'setting/system', 'act' => 'setting_system', 'list_acl' => array()),
            array('name' => '基本信息', 'url' => 'setting/base', 'act' => 'setting_base', 'list_acl' => array()),
            array('name' => 'Email', 'url' => 'setting/email', 'act' => 'setting_email', 'list_acl' => array()),
        )),
        '系统管理员' => array('controller' => 'group', 'act' => 'group', 'class' => 'icon-group', 'action' => array(
            array('name' => '系统管理组', 'url' => 'group/index', 'act' => 'group_index', 'list_acl' => array()),
            array('name' => '系统管理员', 'url' => 'group/user', 'act' => 'group_user', 'list_acl' => array()),
        )),
        '会员' => array('controller' => 'member', 'act' => 'member', 'class' => 'icon-user', 'action' => array(
            array('name' => '会员管理', 'url' => 'member/admin', 'act' => 'member_admin', 'list_acl' => array())
        )),
        '日志管理' => array('controller' => 'log', 'act' => 'log', 'class' => 'icon-exclamation-sign', 'action' => array(
            array('name' => '系统日志', 'url' => 'log/index', 'act' => 'log_index', 'list_acl' => array())
        ))
    );
    private static $userMenu = array(
        '公号管理' => array('controller' => 'user', 'act' => 'index', 'class' => 'fa fa-weixin', 'action' => array(
            array('name' => '我的公号', 'url' => 'user/wechat', 'act' => 'user_wechat', 'list_acl' => array()),
        )),
        '个人信息' => array('controller' => 'user', 'act' => 'info', 'class' => 'fa fa-user', 'action' => array(
            array('name' => '修改账号', 'url' => 'user/info', 'act' => 'user_info', 'list_acl' => array()),
            array('name' => '修改密码', 'url' => 'user/pswd', 'act' => 'user_pswd', 'list_acl' => array()),
        )),
        '会员升级' => array('controller' => 'user', 'act' => 'lv', 'class' => 'fa fa-microphone', 'action' => array(
            array('name' => '充值升级', 'url' => 'user/lv', 'act' => 'user_lv', 'list_acl' => array()),
        ))
    );

    public static function GetUserMenu()
    {
        $menuList = array();
        $controllerId = Yii::app()->controller->getid();
        if ($controllerId == 'user') {
            $menuList = self::$userMenu;
        } else if ($controllerId == 'wechat') {
            $userInfo = Yii::app()->session['userInfo'];
            $groupInfo = GroupModel::model()->find(array('condition' => 'lv=:lv', 'params' => array('lv' => $userInfo['lv'])));
            $actionArray = explode(',', $groupInfo->action);
            $menuList = self::$menuList;
            foreach ($menuList as $k => $m) {
                if (!in_array($m['act'], $actionArray)) {
                    unset($menuList[$k]);
                    foreach ($m['action'] as $sk => $subMenu) {
                        if (!in_array($subMenu['act'], $actionArray)) {
                            unset($menuList[$k]['action'][$sk]);
                        }
                    }
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