<?php
/* @var $this GroupController */

$this->breadcrumbs = array(
    array('name' => '首页', 'url' => array('wechat/index')),
    array('name' => '菜单管理')
);
?>
<div class="page-header">
    <h1>
        菜单管理
    </h1>
</div>
<div class="row">
    <div class="col-xs-12">
        <div class="row">
            <div class="col-xs-12">
                <div class="table-responsive">
                    <div class="tabbable">
                        <ul id="myTab" class="nav nav-tabs">
                            <li class="active">
                                <a>菜单动作</a>
                            </li>
                            <li class="">
                                <a href="#" data-toggle="modal" data-target="#myModal" id="add">添加</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="home">
                                <div class="table-responsive">
                                    <table class="tree table table-striped table-bordered table-hover">
                                        <thead>
                                        <tr>
                                            <th><a href="#" class="">菜单名称</a></th>
                                            <th><a href="#" class="">类型</a></th>
                                            <th><a href="#" class="">菜单值</a></th>
                                            <th>操作</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php if ($menu) { ?>
                                            <?php foreach ($menu as $m) { ?>
                                                <tr class="treegrid-<?php echo $m['id'] ?>">
                                                    <td><?php echo $m['name'] ?></td>
                                                    <?php if ($m['child']) { ?>
                                                        <td></td>
                                                        <td></td>
                                                    <?php } else { ?>
                                                        <td><?php echo isset(GlobalParams::$typeList[$m['type']]) ? GlobalParams::$typeList[$m['type']] : '无' ?></td>
                                                        <td><?php echo $m['type'] == GlobalParams::TYPE_URL ? '' : $m['action'] ?></td>
                                                    <?php } ?>
                                                    <td>
                                                        <div
                                                            class="visible-md visible-lg hidden-sm hidden-xs action-buttons">
                                                            <a title="删除"
                                                               rel="<?php echo Yii::app()->createUrl('menu/delete/id/' . $m['id']) ?>"
                                                               href="javascript:void(0)"
                                                               class="red bootbox-confirm">
                                                                <i class="fa fa-trash-o bigger-130"></i>
                                                            </a>
                                                            <a title="编辑" data-toggle="modal" data-target="#myModal"
                                                               rel="<?php echo Yii::app()->createUrl('menu/update/id/' . $m['id']) ?>"
                                                               data-url='<?php echo Yii::app()->createUrl('menu/getDropDownList', array("parentId" => 0)) ?>'
                                                               href="javascript:void(0)"
                                                               class="green edit">
                                                                <i class="fa fa-pencil bigger-130"></i>
                                                            </a>
                                                            <?php if (!$m['child']) { ?>
                                                                <?php if ($m['type'] == GlobalParams::TYPE_GIFT) { ?>
                                                                    <a>
                                                                        <i class="fa fa-bullhorn"></i>查看礼包</a>
                                                                <?php } elseif ($m['type'] == GlobalParams::TYPE_TEXT) { ?>
                                                                    <a href="javascript:void(0)" class="textReplay"
                                                                       data-id="<?php echo $m['actionId'] ?>"
                                                                       data-url="<?php echo Yii::app()->createUrl('menu/textReplay', array('responseId' => $m['responseId'])); ?>">
                                                                        <i class="fa fa-file-text-o"></i>查看回复
                                                                    </a>
                                                                <?php } elseif ($m['type'] == GlobalParams::TYPE_IMAGE_TEXT) { ?>
                                                                    <a href="javascript:void(0)" class="imageTextReplay"
                                                                       data-url="<?php echo Yii::app()->createUrl('menu/imageTextReplay/actionId/' . $m['actionId']); ?>">
                                                                        <i class="fa fa-file-text"></i>查看回复</a>
                                                                <?php } elseif ($m['type'] == GlobalParams::TYPE_OPEN) { ?>
                                                                    <a class="open"
                                                                        href="javascript:void(0)"
                                                                       data-id="<?php echo $m['actionId'] ?>"
                                                                       data-url="<?php echo Yii::app()->createUrl('menu/open', array('responseId' => $m['responseId'])); ?>"
                                                                        ><i class="fa fa-openid"></i>查看转接</a>
                                                                <?php } elseif ($m['type'] == GlobalParams::TYPE_URL) { ?>
                                                                    <a href="<?php echo $m['action'] ?>"
                                                                       target="_blank"><i class="fa fa-external-link">
                                                                            查看连接</i></a>
                                                                <?php } ?>
                                                            <?php } ?>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <?php if (isset($m['child'])) { ?>
                                                    <?php foreach ($m['child'] as $ch) { ?>
                                                        <tr class="treegrid-<?php echo $ch['id'] ?> treegrid-parent-<?php echo $m['id'] ?>">
                                                            <td><?php echo $ch['name'] ?></td>
                                                            <td><?php echo isset(GlobalParams::$typeList[$ch['type']]) ? GlobalParams::$typeList[$ch['type']] : '无' ?></td>
                                                            <td><?php echo $ch['type'] == GlobalParams::TYPE_URL ? '<a href="' . $ch['action'] . '" target="_blank">' . $ch['action'] . '</a>' : $ch['action'] ?></td>
                                                            <td>
                                                                <div
                                                                    class="visible-md visible-lg hidden-sm hidden-xs action-buttons">
                                                                    <a title="删除"
                                                                       rel="<?php echo Yii::app()->createUrl('menu/delete/id/' . $ch['id']) ?>"
                                                                       href="javascript:void(0)"
                                                                       class="red bootbox-confirm">
                                                                        <i class="fa fa-trash-o bigger-130"></i>
                                                                    </a>
                                                                    <a title="编辑" data-toggle="modal"
                                                                       data-target="#myModal"
                                                                       rel="<?php echo Yii::app()->createUrl('menu/update/id/' . $ch['id']) ?>"
                                                                       data-url='<?php echo Yii::app()->createUrl('menu/getDropDownList', array("parentId" => $m['id'])) ?>'
                                                                       href="javascript:void(0)"
                                                                       class="green edit">
                                                                        <i class="fa fa-pencil bigger-130"></i>
                                                                    </a>

                                                                    <?php if ($ch['type'] == GlobalParams::TYPE_GIFT) { ?>
                                                                        <a>
                                                                            <i class="fa fa-bullhorn"></i>查看礼包</a>
                                                                    <?php } elseif ($ch['type'] == GlobalParams::TYPE_TEXT) { ?>
                                                                        <a href="javascript:void(0)" class="textReplay"
                                                                           data-id="<?php echo $ch['actionId'] ?>"
                                                                           data-url="<?php echo Yii::app()->createUrl('menu/textReplay', array('responseId' => $ch['responseId'])); ?>">
                                                                            <i class="fa fa-file-text-o"></i>查看回复
                                                                        </a>
                                                                    <?php } elseif ($ch['type'] == GlobalParams::TYPE_IMAGE_TEXT) { ?>
                                                                        <a href="javascript:void(0)"
                                                                           class="imageTextReplay"
                                                                           data-url="<?php echo Yii::app()->createUrl('menu/imageTextReplay/actionId/' . $ch['actionId']); ?>">
                                                                            <i class="fa fa-file-text"></i>查看回复</a>
                                                                    <?php } elseif ($ch['type'] == GlobalParams::TYPE_OPEN) { ?>
                                                                        <a class="open"
                                                                            href="javascript:void(0)"
                                                                           data-id="<?php echo $ch['actionId'] ?>"
                                                                           data-url="<?php echo Yii::app()->createUrl('menu/open', array('responseId' => $ch['responseId'])); ?>"
                                                                            ><i class="fa fa-openid"></i>查看转接</a>
                                                                    <?php } elseif ($ch['type'] == GlobalParams::TYPE_URL) { ?>
                                                                        <a href="<?php echo $ch['action'] ?>"
                                                                           target="_blank"><i
                                                                                class="fa fa-external-link">
                                                                                查看连接</i></a>
                                                                    <?php } ?>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    <?php } ?>
                                                <?php } ?>
                                            <?php } ?>
                                        <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div>
                                <?php if(isset($setting)){?> 上次更新时间<?php echo date('Y-m-d H:i:s', $setting->created_at) ?><?php }?>
                                <a class="btn" id="update"><i class="fa fa-check bigger-120">更新</i></a>
                                <a class="btn btn-warning" id="delete"><i class="fa fa-trash-o bigger-120">删除</i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.table-responsive -->
            </div>
            <!-- /span -->
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                        class="sr-only">Close</span></button>
                <h4 class="modal-title" id="myModalLabel">添加菜单</h4>
            </div>
            <?php echo CHtml::beginForm('', 'POST', array('class' => 'form-horizontal', 'id' => 'validation-form')) ?>
            <div class="modal-body">
                <div class="form-group">
                    <?php echo CHtml::label('上级菜单', 'parentId', array('class' => 'col-sm-3 col-lg-2 control-label required')) ?>
                    <div class="col-sm-5 col-lg-3 controls">
                        <?php echo CHtml::dropDownList('parentId', 0, array(), array('class' => 'form-control input-sm')) ?>
                    </div>
                </div>
                <div class="form-group">
                    <?php echo CHtml::label('菜单名称', 'name', array('class' => 'col-sm-3 col-lg-2 control-label required')) ?>
                    <div class="col-sm-5 col-lg-3 controls">
                        <?php echo CHtml::textField('name', '', array('class' => 'form-control input-sm')) ?>
                    </div>
                </div>
                <div id="menuDetail">
                    <div class="form-group">
                        <?php echo CHtml::label('菜单类型', 'type', array('class' => 'col-sm-3 col-lg-2 control-label required')) ?>
                        <div class="col-sm-5 col-lg-3 controls">
                            <?php echo CHtml::dropDownList('type', 0, GlobalParams::$typeList, array('class' => 'form-control input-sm')) ?>
                        </div>
                    </div>
                    <div class="form-group hide" id="URL_TAB">
                        <?php echo CHtml::label('URL', 'url', array('class' => 'col-sm-3 col-lg-2 control-label required')) ?>
                        <div class="col-sm-10 col-lg-8 controls">
                            <?php echo CHtml::textField('url', '', array('class' => 'form-control input-sm')) ?>
                        </div>
                    </div>
                    <div class="form-group" id="ACTION_TAB">
                        <?php echo CHtml::label('菜单值', 'action', array('class' => 'col-sm-3 col-lg-2 control-label required')) ?>
                        <div class="col-sm-5 col-lg-4 controls">
                            <?php echo CHtml::textField('action', '', array('class' => 'form-control input-sm')) ?>
                            <?php echo CHtml::hiddenField('actionId', '', array('class' => 'form-control input-sm')) ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                <?php echo BootStrapUI::saveButton() ?>
            </div>
            <?php echo CHtml::endForm() ?>
        </div>
    </div>
</div>
<!-- Modal Text Replay -->
<div class="modal fade" id="textModal" tabindex="-1" role="dialog" aria-labelledby="textModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                        class="sr-only">Close</span></button>
                <h4 class="modal-title" id="textModalLabel">编辑回复</h4>
            </div>
            <?php echo CHtml::beginForm('', 'POST', array('class' => 'form-horizontal', 'id' => 'validation-text')) ?>
            <div class="modal-body">
                <div class="form-group">

                    <div class="col-sm-5 col-lg-3 controls">
                        <?php echo CHtml::textArea('content', 0, array('class' => 'form-control input-sm', 'style' => "width: 524px; height: 139px;")) ?>
                        <?php echo CHtml::hiddenField('postUrl') ?>
                        <?php echo CHtml::hiddenField('actionId') ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                <?php echo BootStrapUI::saveButton() ?>
            </div>
            <?php echo CHtml::endForm() ?>
        </div>
    </div>
</div>

<!-- Modal Text Image Replay -->
<div class="modal fade" id="textImageModal" tabindex="-1" role="dialog" aria-labelledby="textImageModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" style="width: 1000px">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                        class="sr-only">Close</span></button>
                <h4 class="modal-title" id="textImageModalLabel">编辑回复</h4>
            </div>
            <div class="modal-body">
                <iframe id="frameDialog" height="500px" style="border: none;width:940px;overflow-x : hidden;"
                        scrolling="no" src=""></iframe>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>

            </div>
        </div>
    </div>
</div>
<!-- Modal Text Image Replay -->
<div class="modal fade" id="openModal" tabindex="-1" role="dialog" aria-labelledby="openModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" style="width: 1000px">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                        class="sr-only">Close</span></button>
                <h4 class="modal-title" id="openModalLabel">查看转接</h4>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/treegrid/jquery.treegrid.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/treegrid/jquery.treegrid.bootstrap3.js"></script>
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/treegrid/jquery.treegrid.css"/>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/jquery.validate.min.js"></script>
<script type="text/javascript">
$(document).ready(function () {
    var TYPE_URL = '<?php echo GlobalParams::TYPE_URL?>';
    var postUrl = '';
    $('.tree').treegrid({
        expanderExpandedClass: 'fa fa-minus',
        expanderCollapsedClass: 'fa fa-plus'
    });
    $("#add").click(function () {
        postUrl = '<?php echo Yii::app()->createUrl('menu/create')?>';
        $.get(
            '<?php echo Yii::app()->createUrl('menu/getDropDownList')?>',
            'html',
            function (data) {
                $("#parentId").html(data);
            }
        );
        //清数据
        $("#name").val();
        $("#action").val();
        $("#url").val();
    });
    $(".edit").click(function () {
        postUrl = $(this).attr('rel');
        var selectUrl = $(this).attr('data-url');
        $.get(
            selectUrl,
            'html',
            function (data) {
                $("#parentId").html(data);
            }
        );
        $.getJSON(
            postUrl,
            function (data) {
                if (TYPE_URL == data.type) {
                    $("#URL_TAB").removeClass('hide');
                    $("#ACTION_TAB").addClass('hide');
                } else {
                    $("#URL_TAB").addClass('hide');
                    $("#ACTION_TAB").removeClass('hide');
                }
                $("#type").find('option:selected').removeAttr("selected");
                $("#type").find("option[value='" + data.type + "']").attr("selected", true);
                $("#name").val(data.name);
                $("#action").val(data.action);
                $("#url").val(data.url);
                $("#actionId").val(data.actionId);
            }
        )
    });
    $("#type").change(function () {
        var value = $(this).val();
        if (value == TYPE_URL) {
            $("#URL_TAB").removeClass('hide');
            $("#ACTION_TAB").addClass('hide');
        } else {
            $("#ACTION_TAB").removeClass('hide');
            $("#URL_TAB").addClass('hide');
        }
    });
    $('#validation-form').validate({
        errorElement: 'div',
        errorClass: 'help-block',
        focusInvalid: false,
        rules: {
            name: {
                required: true
            },
            url: {
                required: true,
                url: true
            },
            action: {
                required: true,
                remote: {
                    url: "<?php echo Yii::app()->createUrl('ajax/checkAction')?>", //后台处理程序
                    type: "get",  //数据发送方式
                    dataType: "json",       //接受数据格式
                    data: {                     //要传递的数据
                        action: function () {
                            return $("#action").val();
                        },
                        wechatId:<?php echo $wechatId?>,
                        actionId: function () {
                            return $("#actionId").val();
                        }
                    }
                }
            }
        },
        messages: {
            name: {
                required: "菜单名称不能为空"
            },
            action: {
                required: "菜单值不能为空",
                remote: '菜单值冲突了'
            },
            url: {
                required: 'URL不能为空',
                url: '格式有误'
            }
        },
        highlight: function (e) {
            $(e).closest('.form-group').removeClass('has-info').addClass('has-error');
        },
        success: function (e) {
            $(e).closest('.form-group').removeClass('has-error').addClass('has-info');
            $(e).remove();
        },
        submitHandler: function (form) {
            var name = $("#name").val();
            var parentId = $("#parentId").val();
            var action = $("#action").val();
            var type = $("#type").val();
            var url = $("#url").val();
            $.ajax({
                type: "POST",
                url: postUrl,
                data: "name=" + name + "&parentId=" + parentId + "&action=" + action + "&type=" + type + "&url=" + url,
                dataType: 'json',
                success: function (data) {
                    if (data.status == 1) {
                        alert(data.msg);
                        window.location.href = '';
                    } else {
                        alert(data.msg);
                    }
                }
            });
        },
        invalidHandler: function (form) {
        }
    });
    $("#update").click(function () {
        var obj = $(this);
        obj.attr('disabled', true);
        var i = $(this).find('i');
        var html = i.html();
        i.removeClass().html('<i class="fa fa-spinner fa-spin bigger-140"></i>' + html + '中');
        var url = '<?php echo Yii::app()->createUrl("ajax/updateMenu/wechatId/".$wechatId)?>';
        $.getJSON(
            url,
            function (data) {
                if (data.status == 1) {
                    alert('更新成功');
                } else {
                    alert(data.msg)
                }
                obj.removeAttr('disabled');
                i.removeClass().addClass('fa fa-check bigger-120"').html(html);
            }
        );
    });
    $("#delete").click(function(){
        bootbox.confirm("确定要删除?", function(result) {
            if(result) {
                var obj = $("#delete");
                obj.attr('disabled', true);
                var i = obj.find('i');
                var html = i.html();
                i.removeClass().html('<i class="fa fa-spinner fa-spin bigger-140"></i>' + html + '中');
                var url = '<?php echo Yii::app()->createUrl("ajax/deleteMenu/wechatId/".$wechatId)?>';
                $.getJSON(
                    url,
                    function (data) {
                        if (data.status == 1) {
                            alert('删除成功');
                        } else {
                            alert(data.msg)
                        }
                        obj.removeAttr('disabled');
                        i.removeClass().addClass('fa fa-trash-o bigger-120"').html(html);
                    }
                );
            }
        });
    });
    $(".textReplay").click(function () {
        var Url = $(this).attr('data-url');
        var actionId = $(this).attr('data-id');
        $.getJSON(
            Url,
            function (data) {
                $("#actionId").val(actionId);
                $("#postUrl").val(Url);
                $("#content").val(data.content);
                $('#textModal').modal('show');
            }
        );
    });
    $(".open").click(function () {
        var Url = $(this).attr('data-url');
        var actionId = $(this).attr('data-id');
        $.getJSON(
            Url,
            function (data) {
                $('#openModal').modal('show');
            }
        );
    });
    $('#validation-text').validate({
        errorElement: 'div',
        errorClass: 'help-block',
        focusInvalid: false,
        rules: {
            content: {
                required: true
            }
        },
        messages: {
            content: {
                required: "内容不能为空"
            }
        },
        highlight: function (e) {
            $(e).closest('.form-group').removeClass('has-info').addClass('has-error');
        },
        success: function (e) {
            $(e).closest('.form-group').removeClass('has-error').addClass('has-info');
            $(e).remove();
        },
        submitHandler: function (form) {
            var content = $("#content").val();
            var postUrl = $("#postUrl").val();
            var actionId = $("#actionId").val();
            $.ajax({
                type: "POST",
                url: postUrl,
                data: "content=" + content + '&actionId=' + actionId,
                dataType: 'json',
                success: function (data) {
                    if (data.status == 1) {
                        alert(data.content);
                        window.location.href = '';
                    } else {
                        alert(data.content);
                    }
                }
            });
        },
        invalidHandler: function (form) {
        }
    });
    $(".imageTextReplay").click(function () {
        var Url = $(this).attr('data-url');
        var actionId = $(this).attr('data-id');
        var obj = $('#textImageModal');
        obj.find('iframe').attr('src', Url);
        obj.modal('show');
    })
});
</script>
<style>
    .tree:before {
        border-width: 0px;
    }
</style>