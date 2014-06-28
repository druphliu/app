<?php
/* @var $this LogController */

$this->breadcrumbs=array(
    array('name' => '首页', 'url' => array('site/index')),
    array('name' => '日志'),
);
?>
<div class="page-header">
    <h1>
        管理员日志
        <small>
            <i class="icon-double-angle-right"></i>
            操作日志
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
        <th>用户</th>
        <th>操作</th>
        <th><i class="icon-time bigger-110 hidden-480"></i>时间</th>
        <th></th>
    </tr>
    </thead>

    <tbody>

        <?php foreach($list as $l){?>
        <tr>
            <td class="center">
                <label>
                    <input type="checkbox" class="ace" value="<?=$l['id']?>">
                    <span class="lbl"></span>
                </label>
            </td>
            <td>
                <?=$l['username']?>
            </td>
            <td>
                <?=$l['content']?>
            </td>
            <td>
                <?=date('Y-m-d H:i:s',$l->datetime)?>
            </td>
            <td>
                <div class="visible-md visible-lg hidden-sm hidden-xs btn-group">
                    <button class="btn btn-xs btn-danger">
                        <i class="icon-trash bigger-120"></i>
                    </button>
                </div>
            </td>
         </tr>
        <?php }?>
    </tbody>
    </table>
    </div><!-- /.table-responsive -->
    </div><!-- /span -->
    </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-6">
        <div class="dataTables_info" id="sample-table-2_info">Showing 1 to 10 of 23 entries</div>
    </div>
    <div class="col-sm-6">
        <div class="dataTables_paginate paging_bootstrap">
            <ul class="pagination">
                <li class="prev disabled"><a href="#"><i class="icon-double-angle-left"></i></a></li>
                <li class="active"><a href="#">1</a></li>
                <li><a href="#">2</a></li>
                <li><a href="#">3</a></li>
                <li class="next"><a href="#"><i class="icon-double-angle-right"></i></a></li>
            </ul>
        </div>
    </div>
</div>
