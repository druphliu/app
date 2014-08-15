<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class WechatManagerController extends MemberController{
    /**
     * @var string the default layout for the controller view. Defaults to '//layouts/column1',
     * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
     */
    public $layout='//layouts/mainMember';
    /**
     * @var array context menu items. This property will be assigned to {@link CMenu::items}.
     */
    public $menu=array();

    public $wechatId;
    /**
     * @var array the breadcrumbs of the current page. The value of this property will
     * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
     * for more details on how to specify this property.
     */
    public $breadcrumbs=array();

    public function __construct($id, $module = null){
        parent::__construct($id, $module = null);
        if(Yii::app()->user->isGuest){
            $this->redirect(array('site/login'));
        }else{
            $userInfo = UserModel::model()->find('username=:username', array(':username' => Yii::app()->user->name));
            foreach(GroupModel::model()->findAll() as $g){
                $group[$g->id] = $g;
            };
            Yii::app()->session['group'] = $group;
            Yii::app()->session['userInfo'] = array('uid'=>$userInfo->uid,'username'=>$userInfo->username,'nickname'=>$userInfo->nickname,'lv'=>$userInfo->lv,'groupId'=>$userInfo->groupId);
            //检查权限
        }
    }

    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            $group =Yii::app()->session['group'];
            $userInfo = Yii::app()->session['userInfo'];
            if (strpos($group[$userInfo['groupId']]->action, Yii::app()->controller->id) || strpos($group[$userInfo['groupId']]->action,$action->id) === false) {
                ShowMessage::error('无权限使用此功能，请升级你的账号！');
            }
        }
        return true;
    }

}