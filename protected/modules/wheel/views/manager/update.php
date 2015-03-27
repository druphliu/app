<?php
/* @var $this GroupController */

$this->breadcrumbs = array(
    array('name' => '首页', 'url' => array('wechat/index')),
    array('name' => '营销管理'),
    array('name' => '大转盘','url'=>array('/scratch/manager')),
);
?>
<div class="page-header">
    <h1>
        大转盘
        <small>
            <i class="fa fa-angle-double-right"></i>
            编辑大转盘活动
        </small>
    </h1>
</div>
<?php $this->renderPartial('_form', array('model' => $model,'wechatId'=>$wechatId,'responseId'=>$responseId,'awards'=>$awards));
?>
