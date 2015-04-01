<?php
/* @var $this GroupController */

$this->breadcrumbs = array(
    array('name' => '首页', 'url' => array('wechat/index')),
    array('name' => '营销管理'),
    array('name' => '砸金蛋','url'=>array('/scratch/manager')),
);
?>
<div class="page-header">
    <h1>
        砸金蛋
        <small>
            <i class="fa fa-angle-double-right"></i>
            编辑砸金蛋活动
        </small>
    </h1>
</div>
<?php $this->renderPartial('_form', array('model' => $model,'wechatId'=>$wechatId,'responseId'=>$responseId,'awards'=>$awards));
?>
