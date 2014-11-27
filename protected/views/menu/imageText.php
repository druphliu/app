<?php
/**
 * Created by PhpStorm.
 * User: druphliu
 * Date: 2014/11/21
 * Time: 17:09
 */
?>
<link href="<?php echo Yii::app()->request->baseUrl; ?>/css/appmsg_edit218878.css" type="text/css"
      rel="stylesheet">
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/jquery.validate.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/jquery.form.js"></script>
<div class="row" style="height: 500px;overflow-x: hidden;overflow-y: auto;">
    <?php echo CHtml::beginForm('', 'POST', array('class' => 'form-vertical', 'id' => 'validation-form')) ?>
    <div class="col-lg-12">
    <div class="main_bd">
        <div class="media_preview_area">
            <div class="appmsg multi editing">
                <div class="appmsg_content" id="js_appmsg_preview">
                    <div class="js_appmsg_item " data-id="1" data-fileid="" id="appmsgItem1">
                        <div class="appmsg_info">
                            <em class="appmsg_date"></em>
                        </div>
                        <div class="cover_appmsg_item">
                            <h4 class="appmsg_title">
                                <a target="_blank" onclick="return false;" href="javascript:void(0);"><?php echo isset($focus['title'])?$focus['title']:''?></a>
                            </h4>

                            <div class="appmsg_thumb_wrp">
                                <img src="<?php echo isset($focus['imgUrl'])?$focus['imgUrl']:''?>" class="js_appmsg_thumb appmsg_thumb <?php echo isset($focus['imgUrl'])?'show':''?>">
                                <?php if(isset($focus['imgUrl'])){?>
                                    <i class="appmsg_thumb default">封面图片</i>
                                <?php }?>
                            </div>
                            <div class="appmsg_edit_mask">
                                <a href="javascript:;" data-id="1" class="grey js_edit" onclick="return false;">
                                    <i class="fa fa-pencil bigger-225"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php if($imageTextList){ $i=1;$i++;?>
                    <?php foreach($imageTextList as $list){?>
                            <div class="appmsg_item js_appmsg_item " data-id="<?php echo $i;?>" data-fileid="" id="appmsgItem<?php echo $i;?>">
                                <img src="<?php echo $list['imgUrl']?>" class="js_appmsg_thumb appmsg_thumb show">
                                <h4 class="appmsg_title">
                                    <a target="_blank" href="javascript:void(0);" onclick="return false;"><?php echo $list['title']?></a>
                                </h4>

                                <div class="appmsg_edit_mask">
                                    <a href="javascript:void(0);" onclick="return false;"data-id="<?php echo $i;?>" class="grey js_edit">
                                        <i class="fa fa-pencil bigger-225"></i>
                                    </a>
                                    <a href="javascript:void(0);" onclick="return false;" data-id="<?php echo $i;?>"class="grey js_del"
                                       data-url="<?php echo Yii::app()->createUrl('menu/deleteImgList/id/'.$list['id'])?>">
                                        <i class="fa fa-trash-o bigger-225"></i>
                                    </a>
                                </div>
                            </div>
                    <?php }?>
                    <?php }?>
                </div>
                <a href="javascript:void(0);" id="js_add_appmsg" class="create_access_primary appmsg_add"
                   onclick="return false;">
                    <i class="fa fa-plus bigger-125"></i>
                </a>
            </div>
        </div>
        <div class="media_edit_area">
            <div id="js_appmsg_editor">
                <div class="appmsg_editor" style="margin-top: 0px;">
                    <div class="inner">
                        <i class="arrow arrow_out" style="margin-top: 0px;"></i>
                        <i class="arrow arrow_in" style="margin-top: 0px;"></i>
                        <div class="row">
                            <div class="col-xl-12">
                                <div class="form-group">
                                    <?php echo CHtml::label('标题','title',  array('class' => 'col-sm-12 col-lg-12 control-label no-padding-right')) ?>
                                    <div class="col-sm-12 col-lg-12 controls">
                                        <?php echo CHtml::textField('title',isset($focus['title'])?$focus['title']:'', array('class' => 'form-control input-sm')) ?>
                                    </div>
                                </div>
                                <div class="space-4"></div>
                                <div class="form-group">
                                    <?php echo CHtml::label('描述','summary',  array('class' => 'col-sm-12 col-lg-12 control-label no-padding-right')) ?>
                                    <div class="col-sm-12 col-lg-12 controls">
                                        <?php echo CHtml::textArea('summary',isset($focus['description'])?$focus['description']:'', array('class' => 'form-control input-sm', 'style' => "width: 524px; height: 139px;")) ?>

                                    </div>
                                </div>
                                <div class="space-4"></div>
                                <div class="form-group">
                                    <?php echo CHtml::label('图片路径','imgUrl',  array('class' => 'col-sm-12 col-lg-12 control-label no-padding-right')) ?>
                                    <div class="col-sm-12 col-lg-12 controls">
                                        <?php echo CHtml::textField('imgUrl',isset($focus['imgUrl'])?$focus['imgUrl']:'' , array('class' => 'form-control input-sm')) ?>
                                    </div>
                                </div>
                                <div class="space-4"></div>
                                <div class="form-group">
                                    <?php echo CHtml::label('URL','url',  array('class' => 'col-sm-12 col-lg-12 control-label no-padding-right')) ?>
                                    <div class="col-sm-12 col-lg-12 controls">
                                        <?php echo CHtml::textField('url',isset($focus['url'])?$focus['url']:'', array('class' => 'form-control input-sm')) ?>
                                    </div>
                                </div>
                                <div class="space-4"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <div class="col-lg-12">
        <div class="col-lg-9">
        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
<?php $i = 1;?>
<?php echo CHtml::hiddenField('title1',isset($focus['title'])?$focus['title']:'') ?>
                <?php echo CHtml::hiddenField('summary1',isset($focus['description'])?$focus['description']:'') ?>
                <?php echo CHtml::hiddenField('src1',isset($focus['imgUrl'])?$focus['imgUrl']:'') ?>
                <?php echo CHtml::hiddenField('url1',isset($focus['url'])?$focus['url']:'') ?>
                <?php echo CHtml::hiddenField('id1',isset($focus['id'])?$focus['id']:'') ?>
            <?php if ($imageTextList) {
                ?>
                <?php foreach ($imageTextList as $list) { $i++;?>
                    <?php echo CHtml::hiddenField('title'.$i,$list['title']) ?>
                    <?php echo CHtml::hiddenField('summary'.$i,$list['description']) ?>
                    <?php echo CHtml::hiddenField('src'.$i,$list['imgUrl']) ?>
                    <?php echo CHtml::hiddenField('url'.$i,$list['url']) ?>
                    <?php echo CHtml::hiddenField('id'.$i,$list['id']) ?>
                <?php } ?>

            <?php } ?>
            <?php echo CHtml::hiddenField('count', $i) ?>
        <?php echo BootStrapUI::saveButton() ?>
        </div>
    </div>

    <?php echo CHtml::endForm() ?>
</div>
<script>
    var onId=1;
    $().ready(function(){
        $("#js_add_appmsg").click(function(){
            var count = $("#js_appmsg_preview").children().length+1;
            var html = '<div id="appmsgItem'+count+'" data-fileid="" data-id="'+count+'" class="appmsg_item js_appmsg_item">'+
                '<img class="js_appmsg_thumb appmsg_thumb" src="">'+
                '<i class="appmsg_thumb default">缩略图</i>'+
                '<h4 class="appmsg_title"><a onclick="return false;" href="javascript:void(0);" target="_blank">标题</a>'+
                '</h4>'+
                '<div class="appmsg_edit_mask">'+
                '<a class="grey js_edit" data-id="'+count+'" onclick="return false;" href="javascript:void(0);"><i class="fa fa-pencil bigger-225"></i></a>'+
                '<a class="grey js_del" data-id="'+count+'" onclick="return false;" href="javascript:void(0);"><i class="fa fa-trash-o bigger-225"></i></a>'+
                '</div>'+
                '</div>';
            $("#js_appmsg_preview").append(html);
            var html = '<input id="title'+count+'" type="hidden" name="title'+count+'" value="">'+
                '<input id="src'+count+'" type="hidden" name="src'+count+'" value="">'+
                '<input id="summary'+count+'" type="hidden" name="summary'+count+'" value="">'+
                '<input id="url'+count+'" type="hidden" name="url'+count+'" value="">';
            $("#title1").after(html);
            $("#count").val(count);
        });
        $(document).on('click','.js_edit', function(ev){
            var id = $(this).attr('data-id');
            onId = id;
            if(id==2){
                var height = 200;
            }else if(id==1){
                var height = 0;
            }else{
                var height = 200+120*(id-2);
            }
            var title = $("#title"+onId).val();
            var src = $("#src"+onId).val();
            var summary = $("#summary"+onId).val();
            var url = $("#url"+onId).val();
            $("#title").val(title);
            $("#imgUrl").val(src);
            $("#summary").val(summary);
            $("#url").val(url);
            $(".appmsg_editor").css('margin-top',height);
            $(".has-error").removeClass('has-error');
            $(".help-block").remove();
        });
        $(document).on('click',".js_del",function(){
            //删除有数据的
            var del = false;
            var id = $(this).attr('data-id');
            var url = $(this).attr('data-url');
            $.ajaxSetup({async: false});//修改为同步方式，即可在函数内部修改变量值
            if(url){
                $.getJSON(url, function(json){
                    if(json.status==1){
                        del = true;
                    }
                });
            }
            if((url&&del)||!url){
                $("#appmsgItem"+id).remove();
                var count = $("#js_appmsg_preview").children().length;
                $("#count").val(count);
                $("#title"+id).remove();
                $("#summary"+id).remove();
                $("#src"+id).remove();
                $("#url"+id).remove();
                $("#id"+id).remove();
            }

        });
        $("#title").keyup(function(){
            var value = $(this).val();
            $("#appmsgItem"+onId+" .appmsg_title a").html(value);
            $("#title"+onId).val(value);
        });
        $("#imgUrl").blur(function(){
            var src = $(this).val();
            if(src){
                $("#appmsgItem"+onId+" .js_appmsg_thumb").attr('src',src).show();
                $("#appmsgItem"+onId+" .default").remove();
                $("#src"+onId).val(src);
            }
        });
        $("#summary").blur(function(){
            $("#summary"+onId).val($(this).val())
        });
        $("#url").blur(function(){
            $("#url"+onId).val($(this).val())
        });
        $('#validation-form').validate({
            errorElement: 'div',
            errorClass: 'help-block',
            focusInvalid: false,
            rules: {
                title: {
                    required: true,
                    maxlength: 15
                },
                imgUrl: {
                    required: true,
                    url: true
                },
                summary: {
                    required: true
                },
                url: {
                    required: true,
                    url: true
                }
            },
            messages: {
                title: {
                    required: "标题不能为空",
                    maxlength:"标题不能过长"
                },
                imgUrl: {
                    required: "图片地址不能为空",
                    url: "格式有误"
                },
                summary: "描述不能为空",
                url:{
                    required: "文章地址不能为空",
                    url: "格式有误"
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
                if(element.is(':checkbox') || element.is(':radio')) {
                    var controls = element.closest('div[class*="col-"]');
                    if(controls.find(':checkbox,:radio').length > 1) controls.append(error);
                    else error.insertAfter(element.nextAll('.lbl:eq(0)').eq(0));
                }
                else if(element.is('.select2')) {
                    error.insertAfter(element.siblings('[class*="select2-container"]:eq(0)'));
                }
                else if(element.is('.chosen-select')) {
                    error.insertAfter(element.siblings('[class*="chosen-container"]:eq(0)'));
                }
                else error.insertAfter(element.parent());
            },

            submitHandler: function (form) {
                var options = {
                    dataType: 'json',
                   // beforeSend: function () { $("#btn_Add").val("加载中..."); },
                   // complete: function () { $("#btn_Add").val("上传"); },
                    success: function (data) {
                        if(data.status){
                            alert('编辑成功');
                            window.location.href='';
                        }else{
                            alert(data.msg);
                        };
                    }
                };
                $("#validation-form").ajaxSubmit(options);
            },
            invalidHandler: function (form) {
            }
        });
    })
</script>