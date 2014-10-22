<div class="row">
    <div class="col-xs-12">
        <?php $form = $this->beginWidget('CActiveForm', array(
            'id' => 'group-form',
            'enableAjaxValidation' => false,
            'enableClientValidation' => true,
            'focus' => array($model, 'keyWords'),
            'htmlOptions' => array('class' => 'form-horizontal'),
            'clientOptions' => array(
                'validateOnSubmit' => true,
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
            <?php echo $form->labelEx($model, 'name', array('class' => BootStrapUI::formLabelClass)); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model, 'name', array('class' => 'col-xs-10 col-sm-5')); ?>
                <?php echo $form->error($model, 'name', array('class' => 'help-block col-xs-12 col-sm-reset inline')); ?>
            </div>
        </div>
        <div class="space-4"></div>
        <?php if ($type != GiftModel::TYPE_KEYWORDS) { ?>
            <div class="form-group">
                <?php echo $form->labelEx($model, 'action', array('class' => BootStrapUI::formLabelClass)); ?>
                <div class="col-sm-9">
                    <?php echo $form->textField($model, 'action', array('class' => 'col-xs-10 col-sm-5')); ?>
                    <?php echo $form->error($model, 'action', array('class' => 'help-block col-xs-12 col-sm-reset inline')); ?>
                </div>
            </div>
        <?php } else { ?>
            <div class="form-group">
                <?php echo $form->labelEx($model, 'keywords', array('class' => BootStrapUI::formLabelClass)); ?>
                <div class="col-sm-9">
                    <?php echo $form->textField($model, 'keywords', array('class' => 'col-xs-10 col-sm-5')); ?>
                    <?php echo $form->error($model, 'keywords', array('class' => 'help-block col-xs-12 col-sm-reset inline')); ?>
                </div>
            </div>
            <div class="space-4"></div>
            <div class="form-group">
                <?php echo $form->labelEx($model, 'isAccurate', array('class' => BootStrapUI::formLabelClass)); ?>
                <div class="col-sm-4">
                    <?php echo $form->checkBox($model, 'isAccurate', array('class' => 'col-xs-2 col-sm-2')); ?>
                    <?php echo $form->error($model, 'isAccurate', array('class' => 'help-block col-xs-12 col-sm-reset inline')); ?>
                </div>
            </div>
        <?php } ?>
        <div class="space-4"></div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'openId', array('class' => BootStrapUI::formLabelClass)); ?>
            <div class="col-sm-4">
                <?php echo $form->dropDownList($model,'openId',$open, array('class' => 'col-xs-3 col-sm-3')); ?>
                <?php echo $form->error($model, 'openId', array('class' => 'help-block col-xs-12 col-sm-reset inline')); ?>
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
<!--add project global -->
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/global.js"></script>

<script>
    $().ready(function () {
    });
    $("#submit").click(function () {
        var typeKeywords = <?php if($type==GiftModel::TYPE_KEYWORDS){echo 1;}else{echo 0;}?>;
        if (typeKeywords) {
            var type = '<?php echo OpenReplayModel::OPEN_TYPE?>';
            var responseId = '<?php echo $responseId?>';
            var wechatId = '<?php echo $wechatId?>';
            var url = '<?php echo Yii::app()->createUrl("ajax/checkKeywords")?>';
            return keywordsCheck(wechatId, type, url, 'OpenReplayModel', responseId,'name');
        }
    })

</script>

