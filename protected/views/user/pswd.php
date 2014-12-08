<?php
/* @var $this GroupController */
$this->breadcrumbs=array(
    array('name' => '首页', 'url' => array('wechat/index')),
    array('name' => '个人信息', 'url' => array('index')),
    array('name' => '修改密码'),
);
?>
<div class="page-content">
<div class="page-header">
    <h1>
       修改密码

    </h1>
</div><!-- /.page-header -->
    <?php echo $this->renderPartial('_pswdForm', array('model'=>$model)); ?>
</div>
