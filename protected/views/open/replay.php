<?php
/* @var $this GroupController */

$this->breadcrumbs = array(
    array('name' => '首页', 'url' => array('wechat/index')),
    array('name' => '外接平台管理', 'url' => array('open/index')),
    array('name' => '回复转接管理'),
);
?>
<div class="page-header">
    <h1>
        外接平台管理
        <small>
            <i class="fa fa-angle-double-right"></i>
            回复转接管理
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
                                <a href="<?php echo Yii::app()->createUrl('open/replay') ?>">
                                    关键词转接列表
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo Yii::app()->createUrl('open/replayAdd') ?>"
                                   class="btn btn-primary">添加</a>
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
                                            <th>平台名称</th>
                                            <th>关键词</th>
                                            <th>是否精准匹配</th>
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
                                                        <input type="checkbox" class="ace" name="id"
                                                               value="<?= $d->id ?>">
                                                        <span class="lbl"></span>
                                                    </label>
                                                </td>
                                                <td>
                                                    <?= $i ?>
                                                </td>
                                                <td>
                                                    <?= $d->open_openPlatForm->name ?>
                                                </td>
                                                <td>
                                                    <?php foreach ($d->open_keywords as $keywords) { ?>
                                                        <span
                                                            class="label label-sm label-primary arrowed arrowed-right"><?= $keywords->name ?></span>
                                                    <?php } ?>
                                                </td>
                                                <td>
                                                    <?php foreach ($d->open_keywords as $isAccurate) {
                                                        if ($isAccurate->isAccurate) { ?>
                                                            <span class="label label-sm label-success">是</span>
                                                        <?php } else { ?>
                                                            <span class="label label-sm label-warning">否</span>
                                                        <?php }
                                                        break;
                                                    } ?>
                                                </td>
                                                <td style="width:23%">
                                                    <div class="visible-md visible-lg hidden-sm hidden-xs btn-group">
                                                        <a class="btn btn-xs btn-info"
                                                           href="<?php echo Yii::app()->createUrl('open/replayUpdate/id/' . $d->id) ?>">
                                                            <i class="fa fa-edit bigger-120">编辑</i>
                                                        </a>
                                                        <?php if (!$d->status) { ?>
                                                            <a class="btn btn-xs btn-info status"
                                                               rel="<?php echo Yii::app()->createUrl('ajax/openReplayStatus/id/' . $d->id) ?>"
                                                               data="1" href="javascript:void(0)">
                                                                <i class="fa fa-play bigger-120">启用</i>
                                                            </a>
                                                        <?php } else { ?>
                                                            <a class="btn btn-xs btn-info status"
                                                               rel="<?php echo Yii::app()->createUrl('ajax/openReplayStatus/id/' . $d->id) ?>"
                                                               data="0" href="javascript:void(0)">
                                                                <i class="fa fa-stop bigger-120">停止</i>
                                                            </a>
                                                        <?php } ?>
                                                        <a class="btn btn-xs btn-danger  bootbox-confirm"
                                                           rel="<?= Yii::app()->createUrl('open/replayDelete/id/' . $d->id) ?>">
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
        $(".status").click(function () {
            var status = $(this).attr('data');
            var url = $(this).attr('rel');
            var i = $(this).find('i');
            var html = i.html();
            i.removeClass().html('<i class="fa fa-spinner fa-spin bigger-140"></i>' + html + '中');
            $(this).attr('disabled', 'disabled');
            $.ajax({
                type: 'POST',
                url: url,
                data: "status=" + status,
                dataType: 'json',
                async: false,
                success: function (data) {
                    if (data.result == 0) {
                        window.location.href = '';
                    }
                }
            });
        })
    })
</script>