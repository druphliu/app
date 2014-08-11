<?php
/* @var $this GroupController */
$this->breadcrumbs=array(
    array('name' => '首页', 'url' => array('site/index')),
    array('name' => '用户组管理', 'url' => array('index')),
    array('name' => '创建用户组'),
);
?>
<div class="page-content">
    <div class="page-header">
        <h1>
            创建用户组
            <small>
                <i class="icon-double-angle-right"></i>

            </small>
        </h1>
    </div><!-- /.page-header -->
    <?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
</div>