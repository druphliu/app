<?php
/* @var $this GroupController */
$this->breadcrumbs=array(
    array('name' => '首页', 'url' => array('site/index')),
    array('name' => '公号管理', 'url' => array('index')),
    array('name' => '修改公号'),
);
?>
<div class="page-content">
<div class="page-header">
    <h1>
       修改公众帐号
        <small>
            <i class="fa fa-angle-double-right"></i>
            <?=$model->name?>
        </small>
    </h1>
</div><!-- /.page-header -->
    <?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
</div>
