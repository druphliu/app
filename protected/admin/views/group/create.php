<?php
/* @var $this GroupController */

$this->breadcrumbs = array(
    'Group' => array('/group'),
    'Create',
);
?>
<h1><?php echo $this->id . '/' . $this->action->id; ?></h1>
<div class="form">


    <?php $form = $this->beginWidget('CActiveForm', array(
        'id' => 'group-form',
        'enableAjaxValidation' => true,
        'enableClientValidation' => true,
        'focus' => array($model, 'name'),
    )); ?>

    <?php echo $form->errorSummary($model); ?>

    <div class="row">
        <?php echo $form->labelEx($model, 'name'); ?>
        <?php echo $form->textField($model, 'name'); ?>
        <?php echo $form->error($model, 'name'); ?>
    </div>
    <div class="row">
        <?php echo $form->labelEx($model, 'action'); ?>
            <?php foreach(AdminMenu::$menuList as $name=>$menu){?>
                <?php echo Chtml::checkBox('menu');echo $name."<br>"?>
                <?php foreach($menu['action'] as $k=>$action){?>
                    <?php echo Chtml::checkBoxList($action['act'],$menu['act'],array($action['act']=>$action['name']));?>
                <?php }?>
            <?php }?>
        <?php echo $form->hiddenField($model, 'name'); ?>
        <?php echo $form->error($model, 'action'); ?>
    </div>
    <div class="row submit">
        <?php echo CHtml::submitButton('Login'); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->
<p>
    You may change the content of this page by modifying
    the file <tt><?php echo __FILE__; ?></tt>.
</p>
