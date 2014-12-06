<div class="row">
    <div class="col-xs-12">
        <?php $form = $this->beginWidget('CActiveForm', array(
            'id' => 'group-form',
            'enableAjaxValidation' => false,
            'enableClientValidation' => true,
            'focus' => array($model, 'content'),
            'htmlOptions' => array('class' => 'form-horizontal'),
            'clientOptions'=>array(
                'validateOnSubmit'=>true,
                'afterValidate' => 'js:function(form, data, hasError) {
                  if(hasError) {
                      for(var i in data) $("#"+i).parents(".form-group").addClass("has-error");
                      return false;
                  }
                  else {
                      form.children().removeClass("has-error");
                      return true;
                  }
              }',
                'afterValidateAttribute' => 'js:function(form, attribute, data, hasError) {
                  if(hasError) $("#"+attribute.id).parents(".form-group").addClass("has-error");
                      else $("#"+attribute.id).parents(".form-group").removeClass("has-error");
              }'
            )
        )); ?>
        <?php echo $form->errorSummary($model, BootStrapUI::alertError, '', array('class' => BootStrapUI::alertErrorClass)); ?>

        <div class="form-group">
            <?php echo $form->labelEx($model, 'title', array('class' => BootStrapUI::formLabelClass)); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model, 'title', array('class' => 'col-xs-10 col-sm-5')); ?>
                <?php echo $form->error($model, 'title', array('class' => 'help-block col-xs-12 col-sm-reset inline')); ?>
            </div>
        </div>
        <div class="space-4"></div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'description', array('class' => BootStrapUI::formLabelClass)); ?>
            <div class="col-sm-9">
                <?php echo $form->textArea($model, 'description', array('class' => 'col-xs-10 col-sm-5', 'rows'=>"7")); ?>
                <?php echo $form->error($model, 'description', array('class' => 'help-block col-xs-12 col-sm-reset inline')); ?>
            </div>
        </div>
        <div class="space-4"></div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'url', array('class' => BootStrapUI::formLabelClass)); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model, 'url', array('class' => 'col-xs-10 col-sm-5')); ?>
                <?php echo $form->error($model, 'url', array('class' => 'help-block col-xs-12 col-sm-reset inline')); ?>
            </div>
        </div>
        <div class="space-4"></div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'imgUrl', array('class' => BootStrapUI::formLabelClass)); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model, 'imgUrl', array('class' => 'col-xs-10 col-sm-5')); ?>
                <?php echo $form->error($model, 'imgUrl', array('class' => 'help-block col-xs-12 col-sm-reset inline')); ?>
            </div>
        </div>
        <div class="space-4"></div>
        <div class="clearfix form-actions">
            <div class="col-md-offset-3 col-md-9">
                <?php echo BootStrapUI::saveButton(); ?>
                &nbsp; &nbsp; &nbsp;
                <?php echo BootStrapUI::resetButton(); ?>
                &nbsp; &nbsp; &nbsp;
                <a class="btn btn-primary" href="<?php echo Yii::app()->createUrl('manager/subscribeReplay',array('type'=>TextReplayModel::TEXT_REPLAY_TYPE))?>">切换到文本模式</a>
            </div>
        </div>
        <?php $this->endWidget(); ?>
    </div>
</div>
<script>
    $().ready(function(){

    })
</script>