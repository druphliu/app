<?php
/* @var $this GroupController */

$this->breadcrumbs = array(
    array('name' => '首页', 'url' => array('wechat/index')),
    array('name' => '公众账号'),
);
?>
<div class="page-header">
    <h1>
        公众账号
        <small>
            <i class="fa fa-angle-double-right"></i>
            账号列表
        </small>
    </h1>
</div>
<div class="row">
    <div class="col-xs-12">
        <div class="row">
            <div class="col-xs-12">
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
                            <th>名称</th>
                            <th>操作</th>
                        </tr>
                        </thead>

                        <tbody>

                        <?php foreach ($data as $d) { ?>
                            <tr>
                                <td class="center">
                                    <label>
                                        <input type="checkbox" class="ace" value="<?= $d['name'] ?>">
                                        <span class="lbl"></span>
                                    </label>
                                </td>
                                <td>
                                    <?= $d['name'] ?>
                                </td>
                                <td>
                                    <div class="visible-md visible-lg hidden-sm hidden-xs action-buttons">
                                        <a class="red bootbox-confirm" href="javascript:void(0)"
                                           rel="<?= Yii::app()->createUrl('/wechat/delete/id/' . $d->id) ?>" title="删除">
                                            <i class="fa fa-trash-o bigger-130"></i>
                                        </a>
                                        <a class="green"
                                           href="<?= Yii::app()->createUrl('/wechat/update/id/' . $d->id) ?>"
                                           title="编辑">
                                            <i class="fa fa-pencil bigger-130"></i>
                                        </a>
                                        <a class="btn btn-minier btn-purple"
                                           href="<?= Yii::app()->createUrl('/manager/index/wechatId/' . $d->id) ?>"
                                           title="功能管理">
                                            功能管理
                                        </a>
                                        <a class="btn btn-minier btn-purple api" href="javascript:void(0)"
                                           rel="<?= Yii::app()->createUrl('/wechat/Api/id/' . $d->id) ?>"
                                           title="API接口">
                                            API接口
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
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
    $(".api").click(function () {
        //loading
        $('body').addClass('modal-open');
        $('body').append('<div class="bootbox modal fade in" role="dialog" tabindex="-1" ' +
            'style="display: block;" aria-hidden="false"><div class="modal-dialog" style="width: 100%;text-align: center; padding-top: 80px;">' +
            '<i class="fa fa-spinner fa-spin orange bigger-275"></i>' +
            '</div><div class="modal-backdrop fade in"></div>');
        var url = $(this).attr('rel');
        $.getJSON(url, function (json) {
            bootbox.dialog({
                    title: "API接口",
                    message: '<div class="row"> ' +
                        '<div class="col-md-12"> ' +
                        '<div class="table-responsive">'+
                        '<table class="table table-striped table-bordered table-hover" id="sample-table-1">'+
                        '<thead>'+
                        '<tr><th>TOKEN</th><th>URL</th></tr>'+
                        '</thead>'+
                        '<tbody>' +
                        '<tr><td>'+json.token+'</td><td>'+json.apiUrl+'</td></tr>' +
                        '</tbody>' +
                        '</table>' +
                        '</div>' +
                        '</div> </div>',
                    className: "class-with-width",
                    buttons: {
                        success: {
                            label: "确定",
                            className: "btn-success",
                            callback:function(){
                                //remove loading
                                $(".bootbox").remove();
                                $(".modal-backdrop").remove();
                            }
                        }
                    }
                }
            );
        });
    });
    $().ready(function(){
        $(document).on('click','.bootbox-close-button',function(){
            //remove loading
            $(".bootbox").remove();
            $(".modal-backdrop").remove();
        })
    })
</script>
<style>
    .class-with-width >div{ width: 800px !important; }
</style>