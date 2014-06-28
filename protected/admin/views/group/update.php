<?php
/* @var $this GroupController */
$this->breadcrumbs=array(
    array('name' => '首页', 'url' => array('site/index')),
    array('name' => '用户组管理', 'url' => array('index')),
    array('name' => '更新用户组'),
);
?>
<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>

<h1><?php echo $this->id . '/' . $this->action->id; ?></h1>

<p>
	You may change the content of this page by modifying
	the file <tt><?php echo __FILE__; ?></tt>.
</p>
