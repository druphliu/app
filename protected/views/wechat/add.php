<?php
/* @var $this GroupController */
$this->breadcrumbs=array(
    array('name' => '首页', 'url' => array('wechat/index')),
    array('name' => '公号管理', 'url' => array('index')),
    array('name' => '添加公号'),
);
?>
<div class="page-content">
    <div class="page-header">
        <h1>
            添加公众帐号
            <small>
                <i class="fa fa-angle-double-right"></i>

            </small>
        </h1>
    </div><!-- /.page-header -->
    <?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
</div>