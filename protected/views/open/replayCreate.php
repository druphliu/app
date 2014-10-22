<?php
/* @var $this GroupController */

$this->breadcrumbs = array(
    array('name' => '首页', 'url' => array('site/index')),
    array('name' => '回复转接管理'),
    array('name' => '添加回复'),
);
?>
<div class="page-header">
    <h1>
        回复转接管理
        <small>
            <i class="fa fa-angle-double-right"></i>
            添加回复
        </small>
    </h1>
</div>
<?php $this->renderPartial('_replayForm', array('model' => $model, 'type' => $type, 'wechatId' => $wechatId,
    'responseId' => $responseId, 'open' => $open));
?>
