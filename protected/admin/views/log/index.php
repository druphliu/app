<?php
/* @var $this LogController */

$this->breadcrumbs=array(
	'Log',
);
?>
<h1><?php echo $this->id . '/' . $this->action->id; ?></h1>
<div>
    <?php foreach($list as $l){?>
        <div><span><?=date('Y-m-d H:i:s',$l->datetime)?></span>|<span><?=$l->content?></span></div>
    <?php }?>
</div>
<p>
	You may change the content of this page by modifying
	the file <tt><?php echo __FILE__; ?></tt>.
</p>
