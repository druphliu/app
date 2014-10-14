<div class="row">
    <div class="col-xs-12">
        <?php $form = $this->beginWidget('CActiveForm', array(
            'id' => 'group-form',
            'enableAjaxValidation' => false,
            'enableClientValidation' => true,
            'focus' => array($model, 'name'),
            'htmlOptions' => array('class' => 'form-horizontal')
        )); ?>
        <?php echo $form->errorSummary($model, BootStrapUI::alertError, '', array('class' => BootStrapUI::alertErrorClass)); ?>

        <div class="form-group">
            <?php echo $form->labelEx($model, 'name', array('class' => BootStrapUI::formLabelClass)); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model, 'name', array('class' => 'col-xs-8 col-sm-2')); ?>
                <?php echo $form->error($model, 'name', array('class' => 'help-block col-xs-12 col-sm-reset inline')); ?>
            </div>
        </div>
        <div class="space-4"></div>
        <div class="form-group">
            <?php $disable=false;$actionId=Yii::app()->controller->action->id;if($actionId=='update'):$disable=true;endif;?>
            <?php echo $form->labelEx($model, 'type', array('class' => BootStrapUI::formLabelClass)); ?>
            <div class="col-sm-9">
                <?php echo $form->dropDownList($model, 'type', WechatModel::$typeSelect,array('class' => 'col-xs-8 col-sm-2','disabled'=>$disable)); ?>
                <?php echo $form->error($model, 'type', array('class' => 'help-block col-xs-12 col-sm-reset inline')); ?>
            </div>
        </div>
        <div class="space-4"></div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'originalId', array('class' => BootStrapUI::formLabelClass)); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model, 'originalId', array('class' => 'col-xs-8 col-sm-2','disabled'=>$disable)); ?>
                <?php echo $form->error($model, 'originalId', array('class' => 'help-block col-xs-12 col-sm-reset inline')); ?>
            </div>
        </div>
        <div class="space-4"></div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'wechatAccount', array('class' => BootStrapUI::formLabelClass)); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model, 'wechatAccount', array('class' => 'col-xs-8 col-sm-2')); ?>
                <?php echo $form->error($model, 'wechatAccount', array('class' => 'help-block col-xs-12 col-sm-reset inline')); ?>
            </div>
        </div>
        <div class="space-4"></div>
        <div class="clearfix form-actions">
            <div class="col-md-offset-3 col-md-9">
                <?php echo BootStrapUI::saveButton(); ?>
                &nbsp; &nbsp; &nbsp;
                <?php echo BootStrapUI::resetButton(); ?>
            </div>
        </div>

        <?php $this->endWidget(); ?>
    </div>
</div>
