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
                    <div class="col-sm-5">
                        <div class="wysiwyg-editor" id="editor1"
                             style="height: 115px"><?php echo $model->template ?></div>
                    </div>
                    <?php echo $form->hiddenField($model, 'template', array('id' => 'template')); ?>
                    <?php echo $form->error($model, 'template', array('class' => 'help-block col-xs-12 col-sm-reset inline')); ?>
                    <i>可用标签:{code}</i>
                </div>

            </div>
            <div class="space-4"></div>
            <div class="form-group">
                <?php echo $form->labelEx($model, 'unstartMsg', array('class' => BootStrapUI::formLabelClass)); ?>
                <div class="col-sm-9">
                    <div class="col-sm-5">
                        <div class="wysiwyg-editor" id="editor2"
                             style="height: 115px"><?php echo $model->unstartMsg ?></div>
                    </div>
                    <?php echo $form->hiddenField($model, 'unstartMsg', array('id' => 'unstartMsg')); ?>
                    <?php echo $form->error($model, 'unstartMsg', array('class' => 'help-block col-xs-12 col-sm-reset inline')); ?>

                </div>
            </div>
            <div class="space-4"></div>
            <div class="form-group">
                <?php echo $form->labelEx($model, 'codeOverMsg', array('class' => BootStrapUI::formLabelClass)); ?>
                <div class="col-sm-9">
                    <div class="col-sm-5">
                        <div class="wysiwyg-editor" id="editor3"
                             style="height: 115px"><?php echo $model->codeOverMsg ?></div>
                    </div>
                    <?php echo $form->hiddenField($model, 'codeOverMsg', array('id' => 'codeOverMsg')); ?>
                    <?php echo $form->error($model, 'codeOverMsg', array('class' => 'help-block col-xs-12 col-sm-reset inline')); ?>

                </div>
            </div>
            <div class="space-4"></div>
            <div class="form-group">
                <?php echo $form->labelEx($model, 'endMsg', array('class' => BootStrapUI::formLabelClass)); ?>
                <div class="col-sm-9">
                    <div class="col-sm-5">
                        <div class="wysiwyg-editor" id="editor4"
                             style="height: 115px"><?php echo $model->endMsg ?></div>
                    </div>
                    <?php echo $form->hiddenField($model, 'endMsg', array('id' => 'endMsg')); ?>
                    <?php echo $form->error($model, 'endMsg', array('class' => 'help-block col-xs-12 col-sm-reset inline')); ?>

                </div>
            </div>
            <div class="space-4"></div>
            <div class="form-group">
                <?php echo $form->labelEx($model, 'pauseMsg', array('class' => BootStrapUI::formLabelClass)); ?>
                <div class="col-sm-9">
                    <div class="col-sm-5">
                        <div class="wysiwyg-editor" id="editor5"
                             style="height: 115px"><?php echo $model->pauseMsg ?></div>
                    </div>
                    <?php echo $form->hiddenField($model, 'pauseMsg', array('id' => 'pauseMsg')); ?>
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
<!--add project global -->
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/global.js"></script>

<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/bootstrap-datetimepicker.min.js"></script>
<link href="<?php echo Yii::app()->request->baseUrl; ?>/assets/css/bootstrap-datetimepicker.min.css" rel="stylesheet"/>
<!-- inline scripts related to this page -->
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/jquery-ui-1.10.3.custom.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/jquery.ui.touch-punch.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/markdown/markdown.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/markdown/bootstrap-markdown.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/jquery.hotkeys.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/bootstrap-wysiwyg.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/bootbox.min.js"></script>
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
        $('#editor1').ace_wysiwyg({
            toolbar: [
                {name: 'createLink', className: 'btn-pink'},
                {name: 'unlink', className: 'btn-pink'}
            ]
        }).prev().addClass('wysiwyg-style2');
        $('#editor2').ace_wysiwyg({
            toolbar: [
                {name: 'createLink', className: 'btn-pink'},
                {name: 'unlink', className: 'btn-pink'}
            ]
        }).prev().addClass('wysiwyg-style2');
        $('#editor3').ace_wysiwyg({
            toolbar: [
                {name: 'createLink', className: 'btn-pink'},
                {name: 'unlink', className: 'btn-pink'}
            ]
        }).prev().addClass('wysiwyg-style2');
        $('#editor4').ace_wysiwyg({
            toolbar: [
                {name: 'createLink', className: 'btn-pink'},
                {name: 'unlink', className: 'btn-pink'}
            ]
        }).prev().addClass('wysiwyg-style2');
        $('#editor5').ace_wysiwyg({
            toolbar: [
                {name: 'createLink', className: 'btn-pink'},
                {name: 'unlink', className: 'btn-pink'}
            ]
        }).prev().addClass('wysiwyg-style2');
    });
    $("#submit").click(function () {
        $("#template").val($("#editor1").html());
        $("#unstartMsg").val($("#editor2").html());
        $("#codeOverMsg").val($("#editor3").html());
        $("#endMsg").val($("#editor4").html());
        $("#pauseMsg").val($("#editor5").html());
        if ($("#GiftModel_type").val() == 'keywords') {
            var wechatId = '<?php echo $wechatId?>';
            var url = '<?php echo Yii::app()->createUrl("ajax/checkKeywords")?>';
            return keywordsCheck(wechatId, url, 'GiftModel');
        }
    })

</script>

