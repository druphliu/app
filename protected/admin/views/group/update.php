<?php
/* @var $this GroupController */
$this->breadcrumbs=array(
	'用户组管理'=>array('index'),
	'更新用户组',
);
?>
<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>

<h1><?php echo $this->id . '/' . $this->action->id; ?></h1>

<p>
	You may change the content of this page by modifying
	the file <tt><?php echo __FILE__; ?></tt>.
</p>
