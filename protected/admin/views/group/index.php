<?php
/* @var $this GroupController */

$this->breadcrumbs=array(
	'Group',
);
?>
<h1><?php echo $this->id . '/' . $this->action->id; ?></h1>
<div><a href="<?= Yii::app()->createUrl('/group/create')?>">添加</a></div>
<div><span>ID</span><span>name</span><span>操作</span></div>
<?php foreach($data as $d){?>
    <div><span><?=$d->group_id?></span><span><?=$d->name?></span><span><a href="<?= Yii::app()->createUrl('/group/update/'.$d->group_id)?>">编辑</a>|<a href="<?= Yii::app()->createUrl('/group/delete/'.$d->group_id)?>">删除</a> </span></div>
<?php }?>
<p>
	You may change the content of this page by modifying
	the file <tt><?php echo __FILE__; ?></tt>.
</p>
