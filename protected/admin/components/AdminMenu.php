<?php
/**
 * Created by PhpStorm.
 * User: druphliu
 * Date: 14-6-20
 * Time: 下午10:40
 */

class AdminMenu {
    public static $menuList = array(
        '设置'=>array('controller'=>'setting', 'url'=>'setting/index','act'=>'setting','action'=>array(
            array('name'=>'系统设置','url'=>'setting/system','act'=>'setting_system','list_acl'=>array()),
            array('name'=>'基本信息','url'=>'setting/base','act'=>'setting_base','list_acl'=>array()),
			array('name'=>'Email','url'=>'setting/email','act'=>'setting_email','list_acl'=>array()),
        )),
		'会员'=>array('controller'=>'member','url'=>'member/index','act'=>'member','action'=>array(
			array('name'=>'会员管理','url'=>'member/admin','act'=>'member_admin','list_acl'=>array())
		)),
		'日志管理'=>array('controller'=>'log','url'=>'log/index','act'=>'log','action'=>array(
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
            }
        }
        return $menuList;
    }
} 