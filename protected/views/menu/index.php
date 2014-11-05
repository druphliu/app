<?php
/* @var $this GroupController */

$this->breadcrumbs = array(
    array('name' => '首页', 'url' => array('site/index')),
    array('name' => '帐号管理', 'url' => array('site/index')),
    array('name' => '菜单管理')
)
?>
<div class="page-header">
    <h1>
        菜单管理
        <small>
            <i class="fa fa-angle-double-right"></i>
            菜单列表
        </small>
    </h1>
</div>
<div class="row">
    <div class="col-xs-12">
        <div class="row">
            <div class="col-xs-12">
                <?php echo CHtml::beginForm() ?>
                <div class="col-sm-6">
                    <div class="dd dd-draghandle">
                        <ol class="dd-list" id="dd-list">
                            <?php foreach ($menu as $m) { ?>
                                <li class="dd-item dd2-item item-<?php echo $m['id'] ?>"
                                    data-id="<?php echo $m['id'] ?>">
                                    <div class="dd-handle dd2-handle">
                                        <i class="normal-icon fa fa-arrows blue bigger-130"></i>
                                        <i class="drag-icon fa fa-arrows bigger-125"></i>
                                    </div>
                                    <div class="dd2-content"><input type="text" name="name[<?php echo $m['id'] ?>]"
                                                                    value="<?php echo $m['name'] ?>">

                                        <div class="pull-right action-buttons">
                                            <a href="#" class="blue edit" data-toggle="modal" data-target="#myModal"
                                               data-id="<?php echo $m['id'] ?>">
                                                <i class="fa fa-pencil bigger-130"></i>
                                            </a>

                                            <a href="#" class="red delete" data-id="<?php echo $m['id'] ?>">
                                                <i class="fa fa-trash-o bigger-130"></i>
                                            </a>
                                            <input type="hidden" id="type_<?php echo $m['id'] ?>" name="type_<?php echo $m['id'] ?>"
                                                   value="<?php echo $m['type']==MenuactionModel::TYPE_URL?$m['type']:'action' ?>">
                                            <input type="hidden" id="value_<?php echo $m['id'] ?>" name="value_<?php echo $m['id'] ?>"
                                                   value="<?php echo $m['type'] == MenuactionModel::TYPE_URL ? $m['url'] : $m['action'] ?>">
                                        </div>
                                    </div>
                                    <?php if (isset($m['child'])) { ?>
                                        <ol class="dd-list">
                                            <?php foreach ($m['child'] as $child) { ?>
                                                <li data-id="<?php echo $child['id'] ?>"
                                                    class="dd-item dd2-item item-<?php echo $child['id'] ?>">
                                                    <div class="dd-handle dd2-handle">
                                                        <i class="normal-icon fa blue fa-arrows bigger-130"></i>

                                                        <i class="drag-icon fa fa-arrows bigger-125"></i>
                                                    </div>
                                                    <div class="dd2-content"><input type="text"
                                                                                    name="name[<?php echo $child['id'] ?>]"
                                                                                    value="<?php echo $child['name'] ?>">

                                                        <div class="pull-right action-buttons">
                                                            <a href="#" class="blue edit" data-toggle="modal"
                                                               data-target="#myModal"
                                                               data-id="<?php echo $child['id'] ?>">
                                                                <i class="fa fa-pencil bigger-130"></i>
                                                            </a>

                                                            <a href="#" class="red delete"
                                                               data-id="<?php echo $child['id'] ?>">
                                                                <i class="fa fa-trash-o bigger-130"></i>
                                                            </a>
                                                            <input type="hidden" id="type_<?php echo $child['id'] ?>"
                                                                   name="type_<?php echo $child['id'] ?>"
                                                                   value="<?php echo $child['type']==MenuactionModel::TYPE_URL?$child['type']:'action' ?>">
                                                            <input type="hidden" id="value_<?php echo $child['id'] ?>"
                                                                   name="value_<?php echo $child['id'] ?>"
                                                                   value="<?php echo $child['type'] == MenuactionModel::TYPE_URL ? $child['url'] : $child['action'] ?>">
                                                        </div>
                                                    </div>
                                                </li>
                                            <?php } ?>
                                        </ol>
                                    <?php } ?>
                                </li>
                            <?php } ?>
                        </ol>
                    </div>
                    <a class="btn" id="add">添加</a>
                </div>
                <div class="clearfix form-actions">
                    <div class="col-md-offset-3 col-md-9">
                        <input type="hidden" id="nestable2-output" name="output">
                        <?php echo BootStrapUI::saveButton(); ?>
                        &nbsp; &nbsp; &nbsp;
                        <?php echo BootStrapUI::resetButton(); ?>
                    </div>
                </div>
                <!-- /.table-responsive -->
                <?php echo CHtml::endForm(); ?>
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
                <h4 class="modal-title" id="myModalLabel">Modal title</h4>
            </div>
            <div class="modal-body">
                <div class="tabbable">
                    <ul id="myTab" class="nav nav-tabs">
                        <li class="urlTab">
                            <a href="#urlContent" data-toggle="tab">
                                <i class="green icon-home bigger-110"></i>
                                连接
                            </a>
                        </li>

                        <li class="actionTab">
                            <a href="#actionContent" data-toggle="tab">
                                动作
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane" id="urlContent">
                            <form id="group-form" class="form-horizontal" method="post" action="">
                                <div class="form-group">
                                    <label for="url" class="col-sm-2 control-label no-padding-right required">URL <span
                                            class="required">*</span></label>

                                    <div class="col-sm-9">
                                        <input type="text" maxlength="150" id="url" name="url"
                                               class="col-xs-10 col-sm-10">
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="tab-pane" id="actionContent">
                            <form id="group-form" class="form-horizontal" method="post" action="">
                                <div class="form-group">
                                    <label for="url" class="col-sm-2 control-label no-padding-right required">菜单动作 <span
                                            class="required">*</span></label>

                                    <div class="col-sm-9">
                                        <input type="text" maxlength="150" id="action" name="url"
                                               class="col-xs-10 col-sm-5">

                                        <div class="help-block col-xs-12 col-sm-reset inline">请输入英文字母</div>
                                    </div>

                                </div>
                            </form>
                        </div>
                        <input type="hidden" id="activeId">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                <button type="button" class="btn btn-primary" id="actionSubmit">确定</button>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo Yii::app()->request->baseUrl ?>/assets/js/jquery.nestable.js"></script>
<script type="text/javascript">
    jQuery(function ($) {
        var count = 1;

        var updateOutput = function (e) {
            var list = e.length ? e : $(e.target),
                output = list.data('output');
            if (window.JSON) {
                output.val(window.JSON.stringify(list.nestable('serialize')));//, null, 2));
            } else {
                output.val('JSON browser support required for this demo.');
            }
        };
        $('.dd').nestable({
            maxDepth: 2,
            threshold: 4
        }).on('change', updateOutput);
        ;

        updateOutput($('.dd').data('output', $('#nestable2-output')));
        $("#add").click(function () {
            var html = '<li class="dd-item dd2-item item-add_' + count + '" data-id="add_' + count + '">' +
                '<div class="dd-handle dd2-handle">' +
                '<i class="normal-icon fa fa-arrows blue bigger-130"></i>' +
                '<i class="drag-icon fa fa-arrows bigger-125"></i>' +
                '</div>' +
                '<div class="dd2-content"><input type="text" name="name[add_' + count + ']">' +
                '<div class="pull-right action-buttons">' +
                '<a href="#" class="blue edit" data-toggle="modal" data-target="#myModal" data-id="add_' + count + '">' +
                '<i class="fa fa-pencil bigger-130"></i>' +
                '</a>' +
                '<a href="#" class="red delete" data-id="add_' + count + '">' +
                '<i class="fa fa-trash-o bigger-130"></i>' +
                '</a>'+
                '<input type="hidden" id="type_add_' + count + '" value="" name="type_add_' + count + '">'+
                '<input type="hidden" id="value_add_' + count + '" value="" name="value_add_' + count + '">'+
                '</div></div>' +
                '</li>';
            $("#dd-list").append(html);
            count++;
        });
        $("#actionSubmit").click(function(){
            var type =  value='';
            var id = $("#activeId").val();
            if($('.active',$(".tab-content")).find('input').attr('id')=='url'){
                 type = 'url';
                 value = $("#url").val();
            }else{
                 type = 'action';
                 value = $("#action").val();
            };
            $("#type_"+id).val(type);console.log("#type_"+id);
            $("#value_"+id).val(value);
            $("#myModal").modal("hide");
        });
        $("#dd-list").on('click', '.delete', function () {
            var $thisObj = $(this);
            bootbox.confirm("确认删除?", function (result) {
                if (result) {
                    var id = $thisObj.attr('data-id');
                    $thisObj.parents().find('.item-' + id).remove();
                }
            })
        });
        $("#dd-list").on('click', '.edit', function () {
            var id = $(this).attr('data-id');
            var type = $('#type_'+id).val();
            var value = $("#value_"+id).val();
            var urlType = '<?php echo MenuactionModel::TYPE_URL?>';
            if(type==urlType){
                var $inputObj = $("#url");
                $(".actionTab").removeClass('active');
                $(".urlTab").addClass('active');
                $("#urlContent").addClass('active');
                $("#actionContent").removeClass('active');
            }else{
                var $inputObj = $("#action");
                $(".actionTab").addClass('active');
                $(".urlTab").removeClass('active');
                $("#urlContent").removeClass('active');
                $("#actionContent").addClass('active');
            }
            $("#activeId").val(id);
            $inputObj.val(value);
        });

    });
</script>
<style>
    /*#dd-list .dd-handle {*/
    /*height: 47px;*/
    /*line-height: 47px;*/
    /*width: 47px;*/

    /*}*/

    #dd-list .dd2-content {
        /*margin-left: 10px;*/
        /*padding-top: 0px;*/
        /*padding-bottom: 0px;*/
        /*line-height: 45px;*/
        padding-top: 0px;
        padding-bottom: 0px;
        line-height: 36px;
    }

    /*#dd-list .dd2-item.dd-item > button {*/
    /*margin-left: 46px;*/
    /*margin-top: 9px;*/
    /*}*/
</style>