<?php
/* @var $this GroupController */

$this->breadcrumbs = array(
    array('name' => '首页', 'url' => array('site/index')),
    array('name' => '外接平台管理'),
    array('name' => '平台管理'),
);
?>
<div class="page-header">
    <h1>
        外接平台管理
        <small>
            <i class="fa fa-angle-double-right"></i>
            平台列表
        </small>
    </h1>
</div>
<div class="row">
    <div class="col-xs-12">
        <div class="row">
            <div class="col-xs-12">
                <div class="table-responsive">
                    <div class="tabbable">
                        <ul class="nav nav-tabs" id="myTab">
                            <li class="active">
                                <a>转接平台</a>
                            </li>
                            <li>
                                <a class="btn btn-primary" href="<?php echo Yii::app()->createUrl('open/add')?>">添加</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div id="home" class="tab-pane active">
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
                                        <th>平台名称</th>
                                        <th>是否可用</th>
                                        <th>TOKEN</th>
                                        <th>接口地址</th>
                                        <th>添加时间</th>
                                        <th width="25%"></th>
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
                                                    <input type="checkbox" class="ace" name="id" value="<?= $d->id ?>">
                                                    <span class="lbl"></span>
                                                </label>
                                            </td>
                                            <td>
                                                <?= $i ?>
                                            </td>
                                            <td>
                                                <?= $d->name ?>
                                            </td>
                                            <td><?php if ($d->status) { ?>
                                                    <span class="label label-sm label-success">是</span>
                                                <?php } else { ?>
                                                    <span class="label label-sm label-warning">否</span>
                                                <?php } ?>
                                            </td>
                                            <td><?= $d->token ?></td>
                                            <td><?= $d->apiUrl ?></td>
                                            <td><?= date('Y-m-d H:i:s', $d->created_at) ?></td>
                                            <td style="width:23%">
                                                <div class="visible-md visible-lg hidden-sm hidden-xs btn-group">
                                                    <a class="btn btn-xs btn-info"
                                                       href="<?php echo Yii::app()->createUrl('open/update/id/' . $d->id) ?>">
                                                        <i class="fa fa-edit bigger-120">编辑</i>
                                                    </a>
                                                    <?php if ($d->status == 0) { ?>
                                                        <a class="btn btn-xs btn-info status"
                                                           rel="<?php echo Yii::app()->createUrl('ajax/openStatus/id/' . $d->id) ?>"
                                                           href="javascript:void(0)">
                                                            <i class="fa fa-check bigger-120">检测</i>
                                                        </a>
                                                    <?php } ?>
                                                    <a class="btn btn-xs btn-danger  bootbox-confirm"
                                                       rel="<?= Yii::app()->createUrl('open/delete/id/' . $d->id) ?>">
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

        </div>
    </div>
</div>
<script>
    $().ready(function () {
        $(".status").click(function () {
            var url = $(this).attr('rel');
            var i = $(this).find('i');
            var html = i.html();
            i.removeClass().html('<i class="fa fa-spinner fa-spin bigger-140"></i>' + html + '中');
            $(this).attr('disabled', 'disabled');
            $.ajax({
                type: 'POST',
                url: url,
                dataType: 'json',
                async: false,
                success: function (data) {
                    if (data.result == 0) {
                        alert('检测失败');
                    }
                    window.location.href = '';
                }
            });
        })
    })
</script>