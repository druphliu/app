<?php
/* @var $this GroupController */

$this->breadcrumbs = array(
    array('name' => '首页', 'url' => array('wechat/index')),
    array('name' => '外接平台管理', 'url' => array('open/index')),
    array('name' => '编辑回复'),
);
?>
<div class="page-header">
    <h1>
        回复转接管理
        <small>
            <i class="fa fa-angle-double-right"></i>
            编辑回复
        </small>
    </h1>
</div>
<?php $this->renderPartial('_replayForm', array('model' => $model, 'type' => $type,'wechatId'=>$wechatId,'responseId'=>$responseId, 'open' => $open,'menuList'=>$menuList));
?>
