<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class MemberController extends CController{
    /**
     * @var string the default layout for the controller view. Defaults to '//layouts/column1',
     * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
     */
    public $layout='//layouts/mainMember';
    /**
     * @var array context menu items. This property will be assigned to {@link CMenu::items}.
     */
    public $menu=array();
    /**
     * @var array the breadcrumbs of the current page. The value of this property will
     * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
     * for more details on how to specify this property.
     */
    public $breadcrumbs=array();


    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            if (Yii::app()->user->isGuest) {
                $this->redirect(array('/site/login'));
            } else {
                $userInfo = MemberModel::model()->find('username=:username', array(':username' => Yii::app()->user->name));
                foreach (GroupModel::model()->findAll() as $g) {
                    $group[$g->id] = $g;
                };
                Yii::app()->session['group'] = $group;
                Yii::app()->session['userInfo'] = array('uid' => $userInfo->uid, 'username' => $userInfo->username, 'nickname' => $userInfo->nickname, 'lv' => $userInfo->lv, 'groupId' => $userInfo->groupId);
            }
        }
        return true;
    }
	
	protected function showSuccess($msg = "", $jumpurl = "", $wait = 3)
    {
        self::_jump($msg, $jumpurl, $wait, 1);
    }

    protected function showError($msg = "", $jumpurl = "", $wait = 3)
    {

        self::_jump($msg, $jumpurl, $wait, 0);
    }

    private function _jump($msg = "", $jumpurl = "", $wait = 3, $type = 0)
    {
        $modal = Yii::app()->request->getParam('modal');
        $data = array(
            'msg' => $msg,
            'jumpurl' => $jumpurl,
            'wait' => $wait,
            'type' => $type,
            'modal' => $modal
        );
        $data['title'] = ($type == 1) ? "��ʾ��Ϣ" : "������Ϣ";
        if (empty($jumpurl)) {
            if ($type == 1) {
                $data['jumpurl'] = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : "javascript:window.close();";
            } else {
                $data['jumpurl'] = "javascript:history.back(-1);";
            }
        }
        $this->render("//layouts/showMessage", $data);
        exit;
    }
}