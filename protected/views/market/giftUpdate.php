<?php
/* @var $this GroupController */

$this->breadcrumbs = array(
    array('name' => '首页', 'url' => array('wechat/index')),
    array('name' => '营销管理'),
    array('name' => '礼包码'),
);
?>
<div class="page-header">
    <h1>
        礼包码
        <small>
            <i class="fa fa-angle-double-right"></i>
            编辑礼包
        </small>
    </h1>
</div>
<?php $this->renderPartial('_giftForm', array('model' => $model, 'type' => $type,'wechatId'=>$wechatId,'responseId'=>$responseId,'menuList'=>$menuList));
?>
