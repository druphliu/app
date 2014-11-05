<?php
/* @var $this GroupController */

$this->breadcrumbs = array(
    array('name' => '首页', 'url' => array('site/index')),
    array('name' => '营销管理'),
    array('name' => '礼包管理'),
);
?>
<div class="page-header">
    <h1>
        营销管理
        <small>
            <i class="fa fa-angle-double-right"></i>
            礼包管理
        </small>
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
                                        <?php foreach ($menu as $m) { ?>

                                            <tr class="treegrid-<?php echo $m['id'] ?>">
                                                <td><?php echo $m['name'] ?></td>
                                                <?php if (isset($m['child'])) { ?>
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
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php if (isset($m['child'])) { ?>
                                                <?php foreach ($m['child'] as $ch) { ?>
                                                    <tr class="treegrid-<?php echo $ch['id'] ?> treegrid-parent-<?php echo $m['id'] ?>">
                                                        <td><?php echo $ch['name'] ?></td>
                                                        <td><?php echo isset(GlobalParams::$typeList[$ch['type']]) ? GlobalParams::$typeList[$ch['type']] : '无' ?></td>
                                                        <td><?php echo $ch['type'] == GlobalParams::TYPE_URL ? '<a href="'.$ch['action'].'" target="_blank">'.$ch['action'].'</a>' : $ch['action'] ?></td>
                                                        <td>
                                                            <div
                                                                class="visible-md visible-lg hidden-sm hidden-xs action-buttons">
                                                                <a title="删除"
                                                                   rel="<?php echo Yii::app()->createUrl('menu/delete/id/' . $ch['id']) ?>"
                                                                   href="javascript:void(0)"
                                                                   class="red bootbox-confirm">
                                                                    <i class="fa fa-trash-o bigger-130"></i>
                                                                </a>
                                                                <a title="编辑" data-toggle="modal" data-target="#myModal"
                                                                   rel="<?php echo Yii::app()->createUrl('menu/update/id/' . $ch['id']) ?>"
                                                                   data-url='<?php echo Yii::app()->createUrl('menu/getDropDownList', array("parentId" => $m['id'])) ?>'
                                                                   href="javascript:void(0)"
                                                                   class="green edit">
                                                                    <i class="fa fa-pencil bigger-130"></i>
                                                                </a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                            <?php } ?>
                                        <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div>
                                上次更新时间<?php echo date('Y-m-d H:i:s', $setting->created_at) ?>
                                <a class="btn" id="update">更新</a>
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
                        $("#ACTION_TAB").removeClass('hide').addClass('hide');
                    }
                    $("#name").val(data.name);
                    $("#action").val(data.action);
                    $("#url").val(data.url);
                    $("#type").find("option[value='" + data.type + "']").attr("selected", true);
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
                            wechatId:<?php echo $wechatId?>
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
            var url = '<?php echo Yii::app()->createUrl("ajax/updateMenu",array('wechatId'=>$wechatId))?>';
            $.getJSON(
                url,
                function (data) {
                    if(data.status==1){
                        alert('更新成功');
                    }else{
                        alert(data.msg)
                    }
                }
            )
        })
    });
</script>
<style>
    .tree:before {
        border-width: 0px;
    }
</style>