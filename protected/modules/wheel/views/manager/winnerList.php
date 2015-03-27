<?php
/**
 * Created by PhpStorm.
 * User: druphliu
 * Date: 2015/3/16
 * Time: 21:26
 */
?>

<div class="modal-body">
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover" id="sample-table-1">
            <thead>
            <tr>
                <th>中奖等级</th>
                <th>中奖内容</th>
                <th class="hidden-480">中奖者电话</th>
                <th>
                    中奖时间
                </th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($data as $list){?>
                <tr>
                    <td><?php echo $grades[$list['grade']]?>等奖</td>
                    <td><?php echo $list['code']?></td>
                    <td><?php echo $list['tel']?></td>
                    <td><?php echo date('Y-m-d',$list['datetime'])?></td>
                </tr>
            <?php }?>
            </tbody>
        </table>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <div class="dataTables_info" id="sample-table-2_info"></div>
        </div>
        <div class="col-sm-6">
            <div class="dataTables_paginate paging_bootstrap">
                <?php $this->widget('CLinkPager', Page::go($pages)) ?>
            </div>
        </div>
    </div>
</div>

