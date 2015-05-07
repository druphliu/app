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
                                <a href="#" id="add">添加</a>
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
                                            <th><a href="#" class="">关键词(URL)</a></th>
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
                                                        <td><?php echo Globals::$typeList[$m['type']] ?></td>
                                                        <td><?php echo $m['type'] == Globals::TYPE_URL ? '<a href="' . $m['url'] . '" target="_blank">' . $m['url'] . '</a>' : '<span class="label label-sm label-primary arrowed arrowed-right">'.$m['keywordsName'].'</span>' ?></td>
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
                                                            <a title="编辑"
                                                               rel="<?php echo Yii::app()->createUrl('menu/update/id/' . $m['id']) ?>"
                                                               data-url='<?php echo Yii::app()->createUrl('menu/getDropDownList', array("parentId" => 0)) ?>'
                                                               href="javascript:void(0)"
                                                               class="green js_edit">
                                                                <i class="fa fa-pencil bigger-130"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <?php if (isset($m['child'])) { ?>
                                                    <?php foreach ($m['child'] as $ch) { ?>
                                                        <tr class="treegrid-<?php echo $ch['id'] ?> treegrid-parent-<?php echo $m['id'] ?>">
                                                            <td><?php echo $ch['name'] ?></td>
                                                            <td><?php echo Globals::$typeList[$ch['type']]?></td>
                                                            <td><?php echo $ch['type'] == Globals::TYPE_URL ? '<a href="' . $ch['url'] . '" target="_blank">' . $ch['url'] . '</a>' : '<span class="label label-sm label-primary arrowed arrowed-right">'.$ch['keywordsName'].'</span>' ?></td>
                                                            <td>
                                                                <div
                                                                    class="visible-md visible-lg hidden-sm hidden-xs action-buttons">
                                                                    <a title="删除"
                                                                       rel="<?php echo Yii::app()->createUrl('menu/delete/id/' . $ch['id']) ?>"
                                                                       href="javascript:void(0)"
                                                                       class="red bootbox-confirm">
                                                                        <i class="fa fa-trash-o bigger-130"></i>
                                                                    </a>
                                                                    <a title="编辑"
                                                                       rel="<?php echo Yii::app()->createUrl('menu/update/id/' . $ch['id']) ?>"
                                                                       data-url='<?php echo Yii::app()->createUrl('menu/getDropDownList', array("parentId" => $m['id'])) ?>'
                                                                       href="javascript:void(0)"
                                                                       class="green js_edit">
                                                                        <i class="fa fa-pencil bigger-130"></i>
                                                                    </a>
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
                            <?php echo CHtml::dropDownList('type', 0, Globals::$typeList, array('class' => 'form-control input-sm')) ?>
                        </div>
                    </div>
                    <div class="form-group hide" id="URL_TAB">
                        <?php echo CHtml::label('URL', 'url', array('class' => 'col-sm-3 col-lg-2 control-label required')) ?>
                        <div class="col-sm-10 col-lg-8 controls">
                            <?php echo CHtml::textField('url', '', array('class' => 'form-control input-sm')) ?>
                        </div>
                    </div>
                    <div class="form-group" id="ACTION_TAB">
                        <?php echo CHtml::label('模拟关键词', 'action', array('class' => 'col-sm-3 col-lg-2 control-label required')) ?>
                        <div class="col-sm-5 col-lg-4 controls">
                            <input type="hidden" class="bigdrop" id="e7" style="width:210px"/>

                                    <?php echo CHtml::textField('keywordsId', '',array('style'=>'position: absolute; z-index: -10;')) ?>


                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                <?php echo CHtml::hiddenField('id', '', array('class' => 'form-control input-sm')) ?>
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
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/select2/select2.min.js"></script>
<link href="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/select2/select2.css" rel="stylesheet"/>
<script type="text/javascript">
    $(document).ready(function () {
        var TYPE_URL = '<?php echo Globals::TYPE_URL?>';
        var postUrl = '';
        $('.tree').treegrid({
            expanderExpandedClass: 'fa fa-minus',
            expanderCollapsedClass: 'fa fa-plus'
        });
        $("#add").click(function () {
			showDialogLoading('myModal');
            postUrl = '<?php echo Yii::app()->createUrl('menu/create')?>';
            $.get(
                '<?php echo Yii::app()->createUrl('menu/getDropDownList')?>',
                'html',
                function (data) {
                    $("#parentId").html(data);
                    //清数据
                    $("#name").val();
                    $("#action").val();
                    $("#url").val();
                    closeDialogLoading('myModal');
                }
            );
        });
        $(".js_edit").click(function () {
			showDialogLoading('myModal');
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
                    $("#keywordsId").val(data.keywordsId);
                    data.keywordsName ? $(".select2-chosen").html(data.keywordsName) : '';
                    $("#url").val(data.url);
                    $("#id").val(data.id);
                    closeDialogLoading('myModal');
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
         //   ignore: "input[type='text']:hidden",
            rules: {
                name: {
                    required: true,
                    remote: {
                        url: "<?php echo Yii::app()->createUrl('ajax/checkMenuName')?>", //后台处理程序
                        type: "get",  //数据发送方式
                        dataType: "json",       //接受数据格式
                        data: {                     //要传递的数据
                            name: function () {
                                return $("#name").val();
                            },
                            wechatId:<?php echo $wechatId?>,
                            id:function () {
                                return $("#id").val();
                            }
                        }
                    }
                },
                url: {
                    required: true,
                    url: true
                },
                keywordsId: {
                    required: true,
                    /*remote: {
                        url: "<?php echo Yii::app()->createUrl('ajax/checkMenuKeywords')?>", //后台处理程序
                        type: "get",  //数据发送方式
                        dataType: "json",       //接受数据格式
                        data: {                     //要传递的数据
                            keywordsId: function () {
                                return $("#keywordsId").val();
                            },
                            wechatId:<?php echo $wechatId?>,
                            id:$("#id").val()
                        }
                    }*/
                }
            },
            messages: {
                name: {
                    required: "菜单名称不能为空",
                    remote: '菜单名称冲突了'
                },
                keywordsId: {
                    required: "关键词不能为空",
                    remote: '关键词已经使用了'
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
                var keywordsId = $("#keywordsId").val();
                var type = $("#type").val();
                var url = $("#url").val();
                $.ajax({
                    type: "POST",
                    url: postUrl,
                    data: "name=" + name + "&parentId=" + parentId + "&keywordsId=" + keywordsId + "&type=" + type + "&url=" + url,
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
        var url = '<?php echo Yii::app()->createUrl('ajax/getKeywords')?>';
        $("#e7").select2({
            placeholder: "搜索关键词",
            minimumInputLength: 1,
            ajax: {
                url: url,
                dataType: 'jsonp',
                quietMillis: 100,
                data: function (term, page) { // page is the one-based page number tracked by Select2
                    return {
                        name: term, //search term
                        wechatId:<?php echo $wechatId?>
                    };
                },
                results: function (data, page) {
                    // notice we return the value of more so Select2 knows if more results can be loaded
                    return {results: data.keywords};
                }
            },
            formatResult: movieFormatResult, // omitted for brevity, see the source of this page
            formatSelection: movieFormatSelection, // omitted for brevity, see the source of this page
            dropdownCssClass: "bigdrop", // apply css that makes the dropdown taller
            escapeMarkup: function (m) { return m; } // we do not want to escape markup since we are displaying html in results
        });
    });
    function movieFormatResult(keywords) {
        var markup = "<table class='movie-result'><tr>";
        markup += "<td class='movie-info'><div class='user-name'>" + keywords.name + "</div>";
        markup += "</td></tr></table>";
        return markup;
    }

    function movieFormatSelection(keywords) {
        $("#keywordsId").val(keywords.id);
        return keywords.name;
    }


</script>
<style>
    .tree:before {
        border-width: 0px;
    }
</style>