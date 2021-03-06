<?php
/* @var $this GroupController */

$this->breadcrumbs = array(
    array('name' => '首页', 'url' => array('wechat/index')),
    array('name' => '营销管理'),
    array('name' => '刮刮乐管理'),
);
?>
<div class="page-header">
    <h1>
        营销管理
        <small>
            <i class="fa fa-angle-double-right"></i>
            刮刮乐管理
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
                                <a href="<?php echo Yii::app()->createUrl('scratch') ?>">
                                    活动列表
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo $this->createUrl('manager/create') ?>"
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
                                            <th>活动名称</th>
                                            <th>关键词</th>
                                            <th>是否精准匹配</th>
                                            <th>创建时间</th>
                                            <th>开始时间</th>
                                            <th>结束时间</th>
                                            <th>是否启用</th>
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
                                                    <?= $d->title ?>
                                                </td>
                                                <td>

                                                    <?php foreach ($d->active_keywords as $keywords) { ?>
                                                        <span
                                                            class="label label-sm label-primary arrowed arrowed-right"><?= $keywords->name ?></span>
                                                    <?php } ?>

                                                </td>

                                                <td>
                                                    <?php foreach ($d->active_keywords as $isAccurate) {
                                                        if ($isAccurate->isAccurate) {
                                                            ?>
                                                            <span class="label label-sm label-success">是</span>
                                                        <?php } else { ?>
                                                            <span class="label label-sm label-warning">否</span>
                                                        <?php
                                                        }
                                                        break;
                                                    } ?>
                                                </td>

                                                <td><?= $d->created_at ?></td>
                                                <td><?= $d->startTime ?></td>
                                                <td><?= $d->endTime ?></td>
                                                <td><?php if ($d->status) { ?>
                                                        <span class="label label-sm label-success">是</span>
                                                    <?php } else { ?>
                                                        <span class="label label-sm label-warning">否</span>
                                                    <?php } ?>
                                                </td>
                                                <td style="width:23%">
                                                    <div class="visible-md visible-lg hidden-sm hidden-xs btn-group">
                                                        <?php if (!$d->status) { ?>
                                                            <a class="btn btn-xs btn-info" href="<?php echo $this->createUrl('manager/update/id/' . $d->id) ?>">
                                                                <i class="fa fa-edit bigger-120">编辑</i>
                                                            </a>
                                                            <a class="btn btn-xs btn-info js_status" rel="<?php echo $this->createUrl('manager/status/id/' . $d->id) ?>" data="1" href="javascript:void(0)">
                                                                <i class="fa fa-play bigger-120">启用</i>
                                                            </a>
                                                            <a class="btn btn-xs btn-danger  bootbox-confirm" rel="<?= $this->createUrl('manager/delete/id/' . $d->id) ?>">
                                                                <i class="fa fa-remove bigger-120">删除</i>
                                                            </a>
                                                        <?php } else { ?>
                                                            <a class="btn btn-xs btn-info js_status" rel="<?php echo $this->createUrl('manager/status/id/' . $d->id) ?>"  data="0" href="javascript:void(0)">
                                                                <i class="fa fa-stop bigger-120">停止</i>
                                                            </a>
                                                        <?php } ?>
                                                        <?php if($d->codeType==Globals::ACTIVE_AWARD_TYPE_MIX){?>
                                                            <a href="<?php echo $this->createUrl('manager/codes/id/'.$d->id) ?>" class="btn btn-xs btn-primary">
                                                                <i class="fa fa-list-ol bigger-120">礼包码</i>
                                                            </a>
                                                            <a class="btn btn-xs btn-danger js_winner" id="winner"
                                                               rel="<?php echo $this->createUrl('manager/winnerList/id/'.$d->id) ?>">中奖查询</a>
                                                        <?php }elseif($d->codeType==Globals::ACTIVE_AWARD_TYPE_VIRTUAL){?>
                                                            <a href="<?php echo $this->createUrl('manager/codes/id/'.$d->id) ?>" class="btn btn-xs btn-primary">
                                                                <i class="fa fa-list-ol bigger-120">礼包码</i>
                                                            </a>
                                                        <?php }elseif($d->ispaward){?>
                                                            <a href="<?php echo $this->createUrl('manager/codes/id/'.$d->id) ?>" class="btn btn-xs btn-primary">
                                                                <i class="fa fa-list-ol bigger-120">礼包码</i>
                                                            </a>
                                                        <?php }else{?>
                                                            <a class="btn btn-xs btn-danger js_winner" id="winner"
                                                               rel="<?php echo $this->createUrl('manager/winnerList/id/'.$d->id) ?>">中奖查询</a>
                                                        <?php }?>
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
<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                        class="sr-only">Close</span></button>
                <h4 class="modal-title" id="myModalLabel">中奖者信息</h4>
            </div>
            <iframe id="winnerContent" width="100%" height="400px" frameborder="0"></iframe>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
            </div>
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
        });
        $(".js_winner").click(function () {
            showDialogLoading('myModal');
            var url = $(this).attr('rel');
            $("#winnerContent").attr('src',url);
            $("#winnerContent").load(function(){
                closeDialogLoading('myModal');
            });

        });
        $(".js_status").click(function(){
            var url = $(this).attr('rel');
            var status = $(this).attr('data');
            $.ajax({
                type: 'POST',
                url: url,
                data: "status=" + status,
                dataType: 'json',
                async: false,
                success: function (data) {
                    if (data.result == 0) {
                        window.location.href = '';
                    }else{
                        alert(data.msg);
                    }
                }
            });
        })
    })
</script>