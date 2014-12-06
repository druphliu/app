<link href="<?php echo Yii::app()->request->baseUrl; ?>/css/appmsg_edit218878.css" type="text/css"
      rel="stylesheet">
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/jquery.validate.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/jquery.form.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/jquery.storage.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/imageTextEdit.js"></script>
<div class="row">
    <div class="col-xs-12">
        <?php echo CHtml::beginForm('', 'POST', array('class' => 'form-horizontal', 'id' => 'validation-form')) ?>
        <div class="form-group">
            <?php echo  CHtml::label('关键字','keyword', array('class' => BootStrapUI::formLabelClass)); ?>
            <div class="col-sm-9">
                <?php echo CHtml::textField('keyword',$model['keywords'], array('class' => 'col-xs-10 col-sm-5',
                    'data-url'=>Yii::app()->createUrl("ajax/checkKeywords"),'data-type'=>ImagetextreplayModel::IMAGE_TEXT_REPLAY_TYPE,
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
                                        <a target="_blank" onclick="return false;"
                                           href="javascript:void(0);"><?php echo isset($model['title']) ? $model['title'] : '' ?></a>
                                    </h4>

                                    <div class="appmsg_thumb_wrp">
                                        <img src="<?php echo isset($model['imgUrl']) ? $model['imgUrl'] : '' ?>"
                                             class="js_appmsg_thumb appmsg_thumb <?php echo isset($model['imgUrl']) ? 'show' : '' ?>">
                                        <?php if (!isset($model['imgUrl'])) { ?>
                                            <i class="appmsg_thumb default">封面图片</i>
                                        <?php } ?>
                                    </div>
                                    <div class="appmsg_edit_mask">
                                        <a href="javascript:;" data-id="1" class="grey js_edit" onclick="return false;">
                                            <i class="fa fa-pencil bigger-225"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <?php if ($imageTextList) {
                                $i = 1;
                                ?>
                                <?php foreach ($imageTextList as $list) { $i++; ?>
                                    <div class="appmsg_item js_appmsg_item " data-id="<?php echo $i; ?>" data-fileid=""
                                         id="appmsgItem<?php echo $i; ?>">
                                        <img src="<?php echo $list['imgUrl'] ?>"
                                             class="js_appmsg_thumb appmsg_thumb show">
                                        <h4 class="appmsg_title">
                                            <a target="_blank" href="javascript:void(0);"
                                               onclick="return false;"><?php echo $list['title'] ?></a>
                                        </h4>

                                        <div class="appmsg_edit_mask">
                                            <a href="javascript:void(0);" onclick="return false;"
                                               data-id="<?php echo $i; ?>" class="grey js_edit">
                                                <i class="fa fa-pencil bigger-225"></i>
                                            </a>
                                            <a href="javascript:void(0);" onclick="return false;"
                                               data-id="<?php echo $i; ?>" class="grey js_del"
                                               data-url="<?php echo Yii::app()->createUrl('menu/deleteImgList/id/' . $list['id']) ?>">
                                                <i class="fa fa-trash-o bigger-225"></i>
                                            </a>
                                        </div>
                                    </div>
                                <?php } ?>
                            <?php } ?>
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
                                            <?php echo CHtml::label('标题', 'title', array('class' => 'col-sm-2 col-lg-2 control-label')) ?>
                                            <div class="col-sm-10 col-lg-10 controls">
                                                <?php echo CHtml::textField('title', isset($model['title']) ? $model['title'] : '', array('class' => 'form-control input-sm')) ?>
                                            </div>
                                        </div>
                                        <div class="space-4"></div>
                                        <div class="form-group">
                                            <?php echo CHtml::label('描述', 'summary', array('class' => 'col-sm-2 col-lg-2 control-label')) ?>
                                            <div class="col-sm-1o col-lg-10 controls">
                                                <?php echo CHtml::textArea('summary', isset($model['description']) ? $model['description'] : '', array('class' => 'form-control input-sm', 'style' => "width: 524px; height: 139px;")) ?>

                                            </div>
                                        </div>
                                        <div class="space-4"></div>
                                        <div class="form-group">
                                            <?php echo CHtml::label('图片路径', 'imgUrl', array('class' => 'col-sm-2 col-lg-2 control-label')) ?>
                                            <div class="col-sm-10 col-lg-10 controls">
                                                <?php echo CHtml::textField('imgUrl', isset($model['imgUrl']) ? $model['imgUrl'] : '', array('class' => 'form-control input-sm')) ?>
                                            </div>
                                        </div>
                                        <div class="space-4"></div>
                                        <div class="form-group">
                                            <?php echo CHtml::label('URL', 'url', array('class' => 'col-sm-2 col-lg-2 control-label')) ?>
                                            <div class="col-sm-10 col-lg-10 controls">
                                                <?php echo CHtml::textField('url', isset($model['url']) ? $model['url'] : '', array('class' => 'form-control input-sm')) ?>
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
        <div class="form-group">
            <div class="col-sm-9">
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
        <?php echo CHtml::endForm() ?>
    </div>
</div>
<!--add project global -->
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/global.js"></script>
<script>
    $().ready(function () {
        <?php if(isset($model['title'])){$i=1;?>
        var list = comm = '';
        var location = '[{"title": "<?php echo $model['title']?>","summary":"<?php echo $model['description']?>","src":"<?php echo $model['imgUrl']?>","url":"<?php echo $model['url']?>","id":<?php echo $i?>,"filedId":<?php echo $model['id']?>}';
        <?php if ($imageTextList) {?>
        <?php foreach ($imageTextList as $list) { $i++;?>
        list += comm + '{"title": "<?php echo $list['title']?>","summary":"<?php echo $list['description']?>","src":"<?php echo $list['imgUrl']?>","url":"<?php echo $list['url']?>","id":<?php echo $i?>,"filedId":<?php echo $list['id']?>}';
        comm = ",";
        <?php }?>
        <?php }?>
        list = list ? location + "," + list + "]" : location + "]";
        $.localStorage('editImageText', JSON.stringify(list));
        <?php }else{?>
        $.localStorage('editImageText', '');
        <?php }?>
    })
</script>