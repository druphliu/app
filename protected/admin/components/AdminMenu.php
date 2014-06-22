<?php
/**
 * Created by PhpStorm.
 * User: druphliu
 * Date: 14-6-20
 * Time: 下午10:40
 */

class AdminMenu {
    public static $menuList = array(
        '系统设置'=>array('controller'=>'setting', 'url'=>'default/home','act'=>'setting','action'=>array(
            array('name'=>'系统首页','url'=>'default/home','act'=>'home_index','list_acl'=>array()),
            array('name'=>'站点设置','url'=>'default/setting','act'=>'home_setting','list_acl'=>array()),
        ))
    );
    public static function GetUserMenu(){
        $userInfo = Yii::app()->session['userInfo'];
        $groupInfo = GroupModel::model()->find('group_id=:group_id',$userInfo['group_id']);
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