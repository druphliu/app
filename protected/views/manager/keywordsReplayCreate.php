<?php
/* @var $this GroupController */

$this->breadcrumbs = array(
    array('name' => '首页', 'url' => array('site/index')),
    array('name' => '基础设置'),
    array('name' => '关键词回复'),
);
?>
<div class="page-header">
    <h1>
        关键词回复
        <small>
            <i class="fa fa-angle-double-right"></i>
            添加关键词
        </small>
    </h1>
</div>
<?php if ($type == TextreplayModel::TEXT_REPLAY_TYPE) {
    echo $this->renderPartial('_textKeywordsForm', array('model' => $model, 'wechatId' => $wechatId, 'responseId' => $responseId));
} else {
    echo $this->renderPartial('_imageTextKeywordsForm', array('model' => $model, 'wechatId' => $wechatId, 'responseId' => $responseId));
}?>
