<?php
/* @var $this GroupController */

$this->breadcrumbs = array(
    array('name' => '首页', 'url' => array('wechat/index')),
    array('name' => '基础设置'),
    array('name' => '关注回复'),
);
?>
<div class="page-header">
    <h1>
        关注回复
        <small>
            <i class="fa fa-angle-double-right"></i>
            回复管理
        </small>
    </h1>
</div>
<?php if ($type == TextReplayModel::TEXT_REPLAY_TYPE){
    echo $this->renderPartial('_textSubscribeForm', array('model' => $model));
}else {
    echo $this->renderPartial('/layouts/imageText', array('focus' => $model,'imageTextList'=>$imageTextList));
    //echo $this->renderPartial('_imageTextSubscribeForm', array('model' => $model));
}?>
