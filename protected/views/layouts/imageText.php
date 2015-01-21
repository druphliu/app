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
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/jquery.storage.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/edit.js"></script>
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
                                    <a target="_blank" onclick="return false;"
                                       href="javascript:void(0);"><?php echo isset($focus['title']) ? $focus['title'] : '' ?></a>
                                </h4>

                                <div class="appmsg_thumb_wrp">
                                    <img src="<?php echo isset($focus['imgUrl']) ? $focus['imgUrl'] : '' ?>"
                                         class="js_appmsg_thumb appmsg_thumb <?php echo isset($focus['imgUrl']) ? 'show' : '' ?>">
                                    <?php if (!isset($focus['imgUrl'])) { ?>
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
                            $i = 1; ?>
                            <?php foreach ($imageTextList as $list) {
                                $i++; ?>
                                <div class="appmsg_item js_appmsg_item " data-id="<?php echo $i; ?>" data-fileid=""
                                     id="appmsgItem<?php echo $i; ?>">
                                    <img src="<?php echo $list['imgUrl'] ?>" class="js_appmsg_thumb appmsg_thumb show">
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
                                        <?php echo CHtml::label('标题', 'title', array('class' => 'col-sm-12 col-lg-12 control-label no-padding-right')) ?>
                                        <div class="col-sm-12 col-lg-12 controls">
                                            <?php echo CHtml::textField('title', isset($focus['title']) ? $focus['title'] : '', array('class' => 'form-control input-sm')) ?>
                                        </div>
                                    </div>
                                    <div class="space-4"></div>
                                    <div class="form-group">
                                        <?php echo CHtml::label('描述', 'summary', array('class' => 'col-sm-12 col-lg-12 control-label no-padding-right')) ?>
                                        <div class="col-sm-12 col-lg-12 controls">
                                            <?php echo CHtml::textArea('summary', isset($focus['description']) ? $focus['description'] : '', array('class' => 'form-control input-sm', 'style' => "width: 524px; height: 139px;")) ?>

                                        </div>
                                    </div>
                                    <div class="space-4"></div>
                                    <div class="form-group">
                                        <?php echo CHtml::label('图片路径', 'imgUrl', array('class' => 'col-sm-12 col-lg-12 control-label no-padding-right')) ?>
                                        <div class="col-sm-12 col-lg-12 controls">
                                            <?php echo CHtml::textField('imgUrl', isset($focus['imgUrl']) ? $focus['imgUrl'] : '', array('class' => 'form-control input-sm')) ?>
                                        </div>
                                    </div>
                                    <div class="space-4"></div>
                                    <div class="form-group">
                                        <?php echo CHtml::label('URL', 'url', array('class' => 'col-sm-12 col-lg-12 control-label no-padding-right')) ?>
                                        <div class="col-sm-12 col-lg-12 controls">
                                            <?php echo CHtml::textField('url', isset($focus['url']) ? $focus['url'] : '', array('class' => 'form-control input-sm')) ?>
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
            <?php echo BootStrapUI::saveButton() ?>
            <?php if (Yii::app()->getController()->getAction()->id == 'subscribeReplay') { ?>
                &nbsp; &nbsp; &nbsp;
                <a class="btn btn-primary"
                   href="<?php echo Yii::app()->createUrl('manager/subscribeReplay', array('type' => TextReplayModel::TEXT_REPLAY_TYPE)) ?>">切换到文本模式</a>
            <?php }elseif(Yii::app()->getController()->getAction()->id == 'defaultReplay'){ ?>
                &nbsp; &nbsp; &nbsp;
                <a class="btn btn-primary"
                   href="<?php echo Yii::app()->createUrl('manager/defaultReplay', array('type' => TextReplayModel::TEXT_REPLAY_TYPE)) ?>">切换到文本模式</a>
            <?php }?>
        </div>
    </div>

    <?php echo CHtml::endForm() ?>
</div>
<script>
    $().ready(function () {
        <?php if(isset($focus['title'])){$i=1;?>
        var list = comm = title = summary = src = url = '';
        var id = filedId = 0;
        title = "<?php echo addslashes($focus['title'])?>";
        summary = "<?php echo addslashes($focus['description'])?>";
        src = "<?php echo $focus['imgUrl']?>";
        url = "<?php echo $focus['url']?>";
        id = <?php echo $i?>;
        filedId = <?php echo $focus['id']?>;
        var location = [];
        var item = {};
        item.title = title;
        item.summary = summary;
        item.url = url;
        item.src = src;
        item.id = id;
        item.filedId = filedId;
        location.push(item);
        console.log(location);
        <?php if ($imageTextList) {?>
        <?php foreach ($imageTextList as $list) { $i++;?>
        item = {};
        title = "<?php echo addslashes($list['title'])?>";
        summary = "<?php echo addslashes($list['description'])?>";
        src = "<?php echo $list['imgUrl']?>";
        url = "<?php echo $list['url']?>";
        id = <?php echo $i?>;
        filedId = <?php echo $list['id']?>;
        item.title = title;
        item.summary = summary;
        item.url = url;
        item.src = src;
        item.id = id;
        item.filedId = filedId;
        location.push(item);
        <?php }?>
        <?php }?>
        list = JSON.stringify(location);
        $.localStorage('editImageText', list);
        <?php }else{?>
        $.localStorage('editImageText', '');
        <?php }?>
    })
</script>