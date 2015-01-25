<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/jquery.validate.min.js"></script>
<div class="row">
    <div class="col-xs-12">
        <?php echo CHtml::beginForm('', 'POST', array('class' => 'form-horizontal', 'id' => 'validation-form')) ?>
        <div class="form-group">
            <?php echo CHtml::label('关键词', 'keywords', array('class' => BootStrapUI::formLabelClass)); ?>
            <div class="col-sm-9">
                <?php echo CHtml::textField('keywords',$model['keywords'], array('class' => 'col-xs-10 col-sm-5',
                    'data-url'=>Yii::app()->createUrl("ajax/checkKeywords"),'data-type'=>Globals::TYPE_TEXT,
                    'data-responseId'=>$responseId,'data-wechatId'=>$wechatId)); ?>
            </div>
        </div>
        <div class="space-4"></div>
        <div class="form-group">
            <?php echo CHtml::label('是否精准匹配', 'iaAccurate', array('class' => BootStrapUI::formLabelClass)); ?>
            <div class="col-sm-4">
                <?php echo CHtml::checkBox('isAccurate',$model['isAccurate'], array('class' => 'col-xs-2 col-sm-2')); ?>
            </div>
        </div>
        <div class="space-4"></div>
        <div class="form-group">
            <?php echo CHtml::label('内容', 'content', array('class' => BootStrapUI::formLabelClass)); ?>
            <div class="col-sm-5">
                <div class="wysiwyg-editor" id="editor"><?php echo $model->content ?></div>
                <?php echo CHtml::hiddenField('content',$model['content']); ?>

            </div>
        </div>
        <div class="space-4"></div>
        <div class="clearfix form-actions">
            <div class="col-md-offset-3 col-md-9">
                <?php echo CHtml::hiddenField('TextReplayModel',1)?>
                <?php echo BootStrapUI::saveButton(); ?>
                &nbsp; &nbsp; &nbsp;
                <?php echo BootStrapUI::resetButton(); ?>
            </div>
        </div>
        <?php CHtml::endForm(); ?>
    </div>
</div>
<!--add project global -->
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/global.js"></script>

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
        $("#keywords").focus();
        $('#editor').ace_wysiwyg({
            toolbar: [
                {name: 'createLink', className: 'btn-pink'},
                {name: 'unlink', className: 'btn-pink'}
            ]
        }).prev().addClass('wysiwyg-style2');
        $('#validation-form').validate({
            ignore: "input[type='text']:hidden",
            errorElement: 'div',
            errorClass: 'help-block',
            focusInvalid: false,
            rules: {
                keywords: {
                    required: true,
                    remote: {                                          //验证用户名是否存在
                        type: "POST",
                        url: $("#keywords").attr('data-url'),             //servlet
                        data: {
                            isAccurate: function () {
                                return $("#isAccurate").is(':checked') ? 1 : 0;
                            },
                            responseId: function () {
                                return $("#keywords").attr('data-responseId')
                            },
                            wechatId: function () {
                                return $("#keywords").attr('data-wechatId')
                            },
                            type: function () {
                                return $("#keywords").attr('data-type')
                            }
                        },
                        dataFilter: function (data) {
                            var json = JSON.parse(data);
                            if (json.result == 1) {
                                return '"true"';
                            }
                            return "\"" + json.msg + "\"";
                        }
                    }
                },
                content:{
                    required: true
                }
            },
            messages: {
                keywords: {
                    required: '关键词不能为空',
                    remote: "冲突了"
                },
                content:{
                    required: '回复内容不能为空'
                }
            },
            invalidHandler: function (event, validator) { //display error alert on form submit
                $('.alert-danger', $('.login-form')).show();
            },
            highlight: function (e) {
                $(e).closest('.form-group').removeClass('has-info').addClass('has-error');
            },
            success: function (e) {
                $(e).closest('.form-group').removeClass('has-error').addClass('has-info');
                $(e).remove();
            },
            errorPlacement: function (error, element) {
                if (element.is(':checkbox') || element.is(':radio')) {
                    var controls = element.closest('div[class*="col-"]');
                    if (controls.find(':checkbox,:radio').length > 1) controls.append(error);
                    else error.insertAfter(element.nextAll('.lbl:eq(0)').eq(0));
                }
                else if (element.is('.select2')) {
                    error.insertAfter(element.siblings('[class*="select2-container"]:eq(0)'));
                }
                else if (element.is('.chosen-select')) {
                    error.insertAfter(element.siblings('[class*="chosen-container"]:eq(0)'));
                }
                else error.insertAfter(element.parent());
            }
        });
    });
    $("#submit").click(function () {
        $("#content").val($("#editor").html());
        return true;
    })


</script>