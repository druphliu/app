<div class="row">
    <div class="col-xs-12">
        <?php $form = $this->beginWidget('CActiveForm', array(
            'id' => 'group-form',
            'enableAjaxValidation' => false,
            'enableClientValidation' => true,
            'focus' => array($model, 'keyWords'),
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
            <?php echo $form->labelEx($model, 'type', array('class' => BootStrapUI::formLabelClass)); ?>
            <div class="col-sm-4">
                <?php echo $form->dropDownList($model, 'type', GiftModel::$typeArray, array('class' => 'col-xs-3 col-sm-3')); ?>
                <?php echo $form->error($model, 'type', array('class' => 'help-block col-xs-12 col-sm-reset inline')); ?>
            </div>
        </div>

        <div class="space-4"></div>
        <div id="keyword" <?php if ($type != GiftModel::TYPE_KEYWORDS){ ?>style="display: none" <?php } ?>>
            <div class="form-group">
                <?php echo $form->labelEx($model, 'keyword', array('class' => BootStrapUI::formLabelClass)); ?>
                <div class="col-sm-9">
                    <?php echo $form->textField($model, 'keyword', array('class' => 'col-xs-10 col-sm-5')); ?>
                    <?php echo $form->error($model, 'keyword', array('class' => 'help-block col-xs-12 col-sm-reset inline')); ?>
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
            <div class="space-4"></div>
            <div class="form-group">
                <?php echo $form->labelEx($model, 'startTime', array('class' => BootStrapUI::formLabelClass)); ?>
                <div id="startTime" class="input-group date date-picker col-xs-3 col-sm-3">
                    <?php echo $form->textField($model, 'startTime', array('class' => 'form-control date-picker add-on')); ?>
                    <span class="input-group-addon add-on"><i class="fa fa-calendar"></i></span>
                    <?php echo $form->error($model, 'startTime', array('class' => 'help-block col-xs-12 col-sm-reset inline')); ?>
                </div>
            </div>
            <div class="space-4"></div>
            <div class="form-group">
                <?php echo $form->labelEx($model, 'endTime', array('class' => BootStrapUI::formLabelClass)); ?>
                    <div id="endTime" class="input-group date date-picker col-xs-3 col-sm-3">
                        <?php echo $form->textField($model, 'endTime', array('class' => 'form-control date-picker add-on', 'data-rule-minlength' => 3)); ?>
                        <span class="input-group-addon add-on"><i class="fa fa-calendar"></i></span>


                    <?php echo $form->error($model, 'endTime', array('class' => 'help-block col-xs-12 col-sm-reset inline')); ?>
                </div>
            </div>
            <div class="space-4"></div>
            <div class="form-group">
                <?php echo $form->labelEx($model, 'template', array('class' => BootStrapUI::formLabelClass)); ?>
                <div class="col-sm-9">
                    <?php echo $form->textArea($model, 'template', array('class' => 'col-xs-10 col-sm-5')); ?>
                    <?php echo $form->error($model, 'template', array('class' => 'help-block col-xs-12 col-sm-reset inline')); ?>
                    <i>可用标签:{code}</i>
                </div>

            </div>
            <div class="space-4"></div>
            <div class="form-group">
                <?php echo $form->labelEx($model, 'unstartMsg', array('class' => BootStrapUI::formLabelClass)); ?>
                <div class="col-sm-9">
                    <?php echo $form->textArea($model, 'unstartMsg', array('class' => 'col-xs-10 col-sm-5')); ?>
                    <?php echo $form->error($model, 'unstartMsg', array('class' => 'help-block col-xs-12 col-sm-reset inline')); ?>

                </div>
            </div>
            <div class="space-4"></div>
            <div class="form-group">
                <?php echo $form->labelEx($model, 'codeOverMsg', array('class' => BootStrapUI::formLabelClass)); ?>
                <div class="col-sm-9">
                    <?php echo $form->textArea($model, 'codeOverMsg', array('class' => 'col-xs-10 col-sm-5')); ?>
                    <?php echo $form->error($model, 'codeOverMsg', array('class' => 'help-block col-xs-12 col-sm-reset inline')); ?>

                </div>
            </div>
            <div class="space-4"></div>
            <div class="form-group">
                <?php echo $form->labelEx($model, 'endMsg', array('class' => BootStrapUI::formLabelClass)); ?>
                <div class="col-sm-9">
                    <?php echo $form->textArea($model, 'endMsg', array('class' => 'col-xs-10 col-sm-5')); ?>
                    <?php echo $form->error($model, 'endMsg', array('class' => 'help-block col-xs-12 col-sm-reset inline')); ?>

                </div>
            </div>
            <div class="space-4"></div>
            <div class="form-group">
                <?php echo $form->labelEx($model, 'pauseMsg', array('class' => BootStrapUI::formLabelClass)); ?>
                <div class="col-sm-9">
                    <?php echo $form->textArea($model, 'pauseMsg', array('class' => 'col-xs-10 col-sm-5')); ?>
                    <?php echo $form->error($model, 'pauseMsg', array('class' => 'help-block col-xs-12 col-sm-reset inline')); ?>

                </div>
            </div>
        </div>
        <div class="form-group" id="action"
             <?php if ($type != GiftModel::TYPE_MENU){ ?>style="display: none" <?php } ?>>
            <?php echo $form->labelEx($model, 'action', array('class' => BootStrapUI::formLabelClass)); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model, 'action', array('class' => 'col-xs-10 col-sm-5')); ?>
                <?php echo $form->error($model, 'action', array('class' => 'help-block col-xs-12 col-sm-reset inline')); ?>
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
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/bootstrap-datetimepicker.min.js"></script>
<link href="<?php echo Yii::app()->request->baseUrl; ?>/assets/css/bootstrap-datetimepicker.min.css" rel="stylesheet"/>
<script>
    $().ready(function () {
        $("#GiftModel_type").change(function () {
            var type = $(this).val();
            var TYPE_KEYWORDS = "<?= GiftModel::TYPE_KEYWORDS?>";
            var TYPE_MENU = "<?= GiftModel::TYPE_MENU?>";
            switch (type) {
                case TYPE_KEYWORDS:
                    $("#keyword").show();
                    $("#action").hide();
                    break;
                case TYPE_MENU:
                    $("#keyword").hide();
                    $("#action").show();
                    break;
            }
        });
        $("#startTime").datetimepicker({
            format: 'yyyy-MM-dd hh:mm:ss',
            language: 'zh',
            pickDate: true,
            pickTime: true,
            hourStep: 1,
            minuteStep: 15,
            secondStep: 30,
            inputMask: true
        });
        $("#endTime").datetimepicker({
            format: 'yyyy-MM-dd hh:mm:ss',
            language: 'zh',
            pickDate: true,
            pickTime: true,
            hourStep: 1,
            minuteStep: 15,
            secondStep: 30,
            inputMask: true
        });
    })
</script>