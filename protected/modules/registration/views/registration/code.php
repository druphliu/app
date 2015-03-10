<?php
/* @var $this GroupController */

$this->breadcrumbs = array(
    array('name' => '首页', 'url' => array('wechat/index')),
    array('name' => '营销管理'),
    array('name' => '签到有奖管理','url'=>array('registration')),
);
?>
<div class="page-header">
    <h1>
        营销管理
        <small>
            <i class="fa fa-angle-double-right"></i>
            <a href="<?php echo Yii::app()->createUrl('registration')?>">签到有奖</a>
        </small>
        <small>
            <i class="fa fa-angle-double-right"></i>
            签到有奖奖品管理
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
                            <?php foreach($grades as $grade){?>
                                <li class="<?php if($grade==$currentGrade){?>active<?php }?>">
                                    <a href="<?php echo Yii::app()->createUrl('registration/registration/codes/id/'.$scratchId,array('grade'=>$grade))?>">
                                        签到<?php echo $grade;?>天礼包码
                                    </a>
                                </li>
                            <?php }?>
                            <li>
                                <a href="javascript:void(0)" class="btn btn-primary" id="import">导入</a>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <div class="tab-pane active" id="home">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover" id="sample-table-1">
                                        <thead>
                                        <tr>
                                            <th class="center">
                                                <label>
                                                    <input type="checkbox" class="ace">
                                                    <span class="lbl"></span>
                                                </label>
                                            </th>
                                            <th>ID</th>
                                            <th>版本</th>
                                            <th>CODE</th>
                                            <th>是否领取</th>
                                            <th></th>
                                        </tr>
                                        </thead>

                                        <tbody>
                                        <?php $page = Yii::app()->request->getParam('page', 1);
                                        $i = ($page - 1) * Page::SIZE;
                                        foreach ($data as $d) {
                                            $i++; ?>
                                            <tr>
                                                <td class="center">
                                                    <label>
                                                        <input type="checkbox" class="ace" name="id"
                                                               value="<?= $d['id'] ?>">
                                                        <span class="lbl"></span>
                                                    </label>
                                                </td>
                                                <td>
                                                    <?= $i ?>
                                                </td>
                                                <td>
                                                    <?= Globals::$codeTypeList[$d['type']] ?>
                                                </td>
                                                <td>
                                                    <?php echo $d['code']; //substr_replace($d->code, '*****', 4, 5) ?>
                                                </td>
                                                <td><?php if ($d['openId']) { ?>
                                                        <span class="label label-sm label-success">是</span>
                                                    <?php } else { ?>
                                                        <span class="label label-sm label-warning">否</span>
                                                    <?php } ?>
                                                </td>
                                                <td style="width:23%">
                                                    <div class="visible-md visible-lg hidden-sm hidden-xs btn-group">
                                                        <a class="btn btn-xs btn-danger  bootbox-confirm"
                                                           rel="<?= Yii::app()->createUrl('market/giftCodeDelete/id/' . $d['id']) ?>">
                                                            <i class="fa fa-remove bigger-120">删除</i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
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
<div class="row">
    <div class="col-sm-6">
        <div class="dataTables_info" id="sample-table-2_info">Showing 1 to 10 of 23 entries</div>
    </div>
    <div class="col-sm-6">
        <div class="dataTables_paginate paging_bootstrap">
            <?php $this->widget('CLinkPager', Page::go($pages)) ?>
        </div>
    </div>
</div>
<script>
    $().ready(function () {
        $("#import").click(function () {
            bootbox.dialog({
                    title: "导入礼包码",
                    message: '<div class="row"> ' +
                        '<div class="col-md-12"> ' +
                        '<form class="form-horizontal" id="myform"  method="post" ' +
                        'action="<?php echo Yii::app()->createUrl('registration/registration/codeImport')?>" ' +
                        'enctype="multipart/form-data"> ' +
                        '<div class="form-group"> ' +
                        '<div class="col-md-8"> ' +
                        '<input type="file" id="id-input-file-2" name="code"/>' +
                        '<input type="hidden" name="activeId" id="activeId" value="<?=$scratchId?>" />' +
                        '<i>仅支持txt格式文件,参考格式<a target="_blank" href="upload/eg.txt">查看</a></i></div> ' +
                        '<div class="col-md-8"> ' +
                        '<select name="type" id="type">' +
                        '<option value="<?php echo Globals::CODE_TYPE_LEGAL?>" selected="selected">正版</option>' +
                        '<option value="<?php echo Globals::CODE_TYPE_UNLEGAL?>">混版</option>' +
                        '</select>' +
                        '</div>'+
                        '</form> </div> </div>',
                    buttons: {
                        success: {
                            label: "导入",
                            className: "btn-success",
                            callback: function () {
                                var file = $('input[type=file]')[0].files[0];
                                if(!file){
                                    alert('请选择文件');
                                    return;
                                }
                                //loading
                                $('body').addClass('modal-open');
                                $('body').append('<div class="bootbox modal fade in" role="dialog" tabindex="-1" ' +
                                    'style="display: block;" aria-hidden="false"><div class="modal-dialog" ' +
                                    'style="width: 100%;text-align: center; padding-top: 80px;">' +
                                    '<i class="fa fa-spinner fa-spin orange bigger-275"></i>' +
                                    '</div><div class="modal-backdrop fade in"></div>');
                                var data = new FormData($(this).parents("form").get(0));
                                data.append('file', file);
                                data.append('activeId', $('#activeId').val());
                                data.append('grade',<?php echo $currentGrade?>);
                                data.append('type', $('#type').val())
                                $.ajax({
                                    type: 'POST',
                                    url: '<?php echo Yii::app()->createUrl('registration/registration/codeImport')?>',
                                    data: data,
                                    /**
                                     *必须false才会自动加上正确的Content-Type
                                     */
                                    contentType: false,
                                    /**
                                     * 必须false才会避开jQuery对 formdata 的默认处理
                                     * XMLHttpRequest会对 formdata 进行正确的处理
                                     */
                                    processData: false
                                }).then(function (msg) {
                                    //remove loading
                                    $(".bootbox").remove();
                                    $(".modal-backdrop").remove();
                                    //doneCal
                                    alert(msg);
                                    window.location.href = '';
                                }, function () {
                                    //failCal
                                    alert('failed');
                                });

                            }
                        }
                    }
                }
            );
            $('#id-input-file-2').ace_file_input({
                no_file: '请选择导入码 ...',
                btn_choose: '选择',
                btn_change: '取消',
                droppable: false,
                onchange: null,
                thumbnail: false, //| true | large
                whitelist: 'text',
                before_change: function(files, dropped) {
                    var file = files[0];
                    if(file.type!='text/plain'){
                        alert('格式有误');
                        return false;
                    }
                    return true;
                }
            });
        });

    })
</script>