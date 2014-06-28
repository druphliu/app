<?php
/**
 * Created by PhpStorm.
 * User: druphliu
 * Date: 14-6-20
 * Time: 下午10:40
 */

class AdminMenu extends CWidget{
    public $menus;
    public static $menuList = array(
        '设置'=>array('controller'=>'setting', 'act'=>'setting','class'=>'icon-dashboard','action'=>array(
            array('name'=>'系统设置','url'=>'setting/system','act'=>'setting_system','list_acl'=>array()),
            array('name'=>'基本信息','url'=>'setting/base','act'=>'setting_base','list_acl'=>array()),
			array('name'=>'Email','url'=>'setting/email','act'=>'setting_email','list_acl'=>array()),
        )),
        '系统管理员'=>array('controller'=>'group','act'=>'group','class'=>'icon-group','action'=>array(
            array('name'=>'系统管理组','url'=>'group/index','act'=>'group_index','list_acl'=>array()),
            array('name'=>'系统管理员','url'=>'group/user','act'=>'group_user','list_acl'=>array()),
        )),
		'会员'=>array('controller'=>'member','act'=>'member','class'=>'icon-user','action'=>array(
			array('name'=>'会员管理','url'=>'member/admin','act'=>'member_admin','list_acl'=>array())
		)),
		'日志管理'=>array('controller'=>'log','act'=>'log','class'=>'icon-exclamation-sign','action'=>array(
			array('name'=>'系统日志','url'=>'log/index','act'=>'log_index','list_acl'=>array())
		))
    );
    public static function GetUserMenu(){
        $userInfo = Yii::app()->session['userInfo'];
        $groupInfo = GroupModel::model()->find(array('condition'=>'group_id=:group_id','params'=>array('group_id'=>$userInfo['group_id'])));
        $actionArray = explode(',',$groupInfo->action);
        $menuList = self::$menuList;
        foreach($menuList as $k=>$m){
            if(!in_array($m['act'],$actionArray)){
                unset($menuList[$k]);
                foreach($m['action'] as $sk=>$subMenu){
                    if(!in_array($subMenu['act'],$actionArray)){
                        unset($menuList[$k]['action'][$sk]);
                    }
                }
            }
        }
        return $menuList;
    }

    public function run() {
        $this->menus = AdminMenu::GetUserMenu();
//        foreach($menu as $name=>$m){
//            $menuArray[] = array('label'=>$name,'url'=>array($m['url']));
//        }
//        $extraMenu = array(array('label'=>'Home', 'url'=>array('/site/index')),
//            array('label'=>'About', 'url'=>array('/site/page', 'view'=>'about')),
//            array('label'=>'Contact', 'url'=>array('/site/contact')),
//            array('label'=>'Login', 'url'=>array('/site/login'), 'visible'=>Yii::app()->user->isGuest),
//            array('label'=>'Logout ('.Yii::app()->user->name.')', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest)
//        );
//        $this->menus = array_merge($menuArray,$extraMenu);
        $this->render('adminMenu');
    }
} 