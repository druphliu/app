<?php
/* @var $this GroupController */

$this->breadcrumbs = array(
    array('name' => '首页', 'url' => array('site/index')),
    array('name' => '营销管理'),
    array('name' => '礼包码管理'),
);
?>
<div class="page-header">
    <h1>
        营销管理
        <small>
            <i class="fa fa-angle-double-right"></i>
            礼包码管理
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
                                <a href="#">
                                    礼包码列表
                                </a>
                            </li>
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
                                                               value="<?= $d->id ?>">
                                                        <span class="lbl"></span>
                                                    </label>
                                                </td>
                                                <td>
                                                    <?= $i?>
                                                </td>
                                                <td>
                                                    <?php echo substr_replace($d->code, '*****', 4, 5) ?>
                                                </td>
                                                <td><?php if ($d->openId) { ?>
                                                        <span class="label label-sm label-success">是</span>
                                                    <?php } else { ?>
                                                        <span class="label label-sm label-warning">否</span>
                                                    <?php } ?>
                                                </td>
                                                <td style="width:23%">
                                                    <div class="visible-md visible-lg hidden-sm hidden-xs btn-group">
                                                        <a class="btn btn-xs btn-danger  bootbox-confirm"
                                                           rel="<?= Yii::app()->createUrl('market/giftCodeDelete/id/' . $d->id) ?>">
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
                        'action="<?php echo Yii::app()->createUrl('market/codeImport')?>" '+
                        'enctype="multipart/form-data"> ' +
                        '<div class="form-group"> ' +
                        '<div class="col-md-8"> ' +
                        '<input type="file" id="id-input-file-2" name="code"/>' +
                        '<input type="hidden" name="giftId" value="<?=$giftId?>" />' +
                        '</div> ' +
                        '</form> </div> </div>',
                    buttons: {
                        success: {
                            label: "导入",
                            className: "btn-success",
                            callback: function () {
                                    var data=new FormData($(this).parents("form").get(0));
                                    data.append('file', $('input[type=file]')[0].files[0]);
                                    data.append('giftId',$('input[type=hidden]').val());
                                    $.ajax({
                                        type:'POST',
                                        url:'<?php echo Yii::app()->createUrl('market/codeImport')?>',
                                        data:data,
                                        /**
                                         *必须false才会自动加上正确的Content-Type
                                         */
                                        contentType:false,
                                        /**
                                         * 必须false才会避开jQuery对 formdata 的默认处理
                                         * XMLHttpRequest会对 formdata 进行正确的处理
                                         */
                                        processData:false
                                    }).then(function(msg){
                                        //doneCal
                                        alert(msg);
                                        window.location.href='';
                                    },function(){
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
                whitelist: 'gif|png|jpg|jpeg'
            });
        });

    })
</script>