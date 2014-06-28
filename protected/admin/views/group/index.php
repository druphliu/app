<?php
/* @var $this GroupController */

$this->breadcrumbs=array(
    array('name' => '首页', 'url' => array('site/index')),
    array('name' => '用户组'),
);
?>
<div class="page-header">
    <h1>
        管理组
        <small>
            <i class="icon-double-angle-right"></i>
            管理组列表
        </small>
    </h1>
    <a href="<?= Yii::app()->createUrl('/group/create')?>">添加</a>
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

                        <?php foreach($data as $d){?>
                            <tr>
                                <td class="center">
                                    <label>
                                        <input type="checkbox" class="ace" value="<?=$d['group_id']?>">
                                        <span class="lbl"></span>
                                    </label>
                                </td>
                                <td>
                                    <?=$d['name']?>
                                </td>
                                <td>
                                    <div class="visible-md visible-lg hidden-sm hidden-xs btn-group">
                                        <a class="btn btn-xs btn-success"
                                           href="<?= Yii::app()->createUrl('/group/view/id/' . $d->group_id) ?>"
                                           class="blue">
                                            <i class="icon-zoom-in bigger-130"></i>
                                        </a>
                                        <a class="btn btn-xs btn-danger bootbox-confirm"
                                           rel="<?= Yii::app()->createUrl('/group/delete/id/' . $d->group_id) ?>">
                                            <i class="icon-trash bigger-120"></i>
                                        </a>
                                        <a class="btn btn-xs btn-info"
                                           href="<?= Yii::app()->createUrl('/group/update/id/' . $d->group_id) ?>">
                                            <i class="icon-edit bigger-120"></i>
                                        </a>
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
