<?php

/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class WechatManagerController extends MemberController
{
    /**
     * @var string the default layout for the controller view. Defaults to '//layouts/column1',
     * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
     */
    public $layout = '//layouts/mainMember';
    /**
     * @var array context menu items. This property will be assigned to {@link CMenu::items}.
     */
    public $menu = array();

    /**
     * @var array the breadcrumbs of the current page. The value of this property will
     * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
     * for more details on how to specify this property.
     */
    public $breadcrumbs = array();

    public $wechatInfo;

    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            $group = Yii::app()->session['group'];
            $userInfo = Yii::app()->session['userInfo'];
//            if (strpos($group[$userInfo['groupId']]->action, Yii::app()->controller->id) || strpos($group[$userInfo['groupId']]->action, $action->id) === false) {
//                ShowMessage::error('无权限使用此功能，请升级你的账号！');
//            }
            $paramId = intval(Yii::app()->request->getParam('wechatId', 0));
            $wechatId = $paramId ? $paramId : Yii::app()->session['wechatId'];
            if ($paramId)
                Yii::app()->session['wechatId'] = $paramId;
            $this->wechatInfo = $this->_getWechatInfo($wechatId);
            Yii::app()->session['isAuth'] = $this->wechatInfo->isAuth;
        }
        return true;
    }

    protected function _getWechatInfo($id)
    {
        $wechatInfo = WechatModel::model()->findByPk($id);
        if (!$wechatInfo || $wechatInfo->uid != Yii::app()->session['userInfo']['uid']) {
            ShowMessage::error('公众帐号不存在!');
        }
        return $wechatInfo;
    }

    protected function saveImageText($count, $data, $id)
    {
        $result = false;
        $validate = true;
        $parentId = $id;
        /* $model = ImagetextreplayModel::model()->with('imagetextreplay_keywords')->find('t.id=:id and
         imagetextreplay_keywords.type=:type', array(':id' => $id, ':type' => ImagetextreplayModel::IMAGE_TEXT_REPLAY_TYPE));*/
        $imageTextList = ImagetextreplayModel::model()->findAll('parentId=:parentId', array(':parentId' =>$parentId ));
        $listData = CHtml::listData($imageTextList, 'id', 'id');
        if ($count >= 2 && $id) {
            for ($i = 2; $i <= $count; $i++) {
                $id = $data['id' . $i] ? $data['id' . $i] : 0;
                if ($id) {
                    ${'model' . $i} = ImagetextreplayModel::model()->findByPk($id);
                    if (in_array($id, $listData))
                        unset($listData[$id]);
                }
                ${'model' . $i} = isset(${'model' . $i}) ? ${'model' . $i} : new ImagetextreplayModel();
                ${'model' . $i}->title = $data['title' . $i];
                ${'model' . $i}->description = $data['summary' . $i];
                ${'model' . $i}->type = Globals::TYPE_BASE_REPLAY;
                ${'model' . $i}->imgUrl = $data['src' . $i];
                ${'model' . $i}->url = $data['url' . $i];
                ${'model' . $i}->wechatId = $this->wechatInfo->id;
                ${'model' . $i}->parentId = $parentId;
                $validate &= ${'model' . $i}->validate();
            }
            if ($validate) {
                for ($i = 2; $i <= $count; $i++) {
                    ${'model' . $i}->save();
                }
                if ($listData) {
                    foreach ($listData as $id) {
                        ImagetextreplayModel::model()->deleteByPk($id);
                    }
                }
                $result = true;
            }
        }
        return $result;
    }
}