<?php
/* @var $this VipController */

$this->breadcrumbs=array(
    array('name' => '首页', 'url' => array('wechat/index')),
    array('name' => '会员升级', 'url' => array('index')),
    array('name' => '充值升级'),
);
?>
<div class="page-content">
    <div class="page-header">
        <h1>
            充值升级

        </h1>
    </div><!-- /.page-header -->
    <?php echo $this->renderPartial('_form', array('model'=>$model,'priceList'=>$priceList,'mouth'=>$mouth)); ?>
</div>

