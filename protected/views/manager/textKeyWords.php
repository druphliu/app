<?php
/* @var $this GroupController */

$this->breadcrumbs=array(
    array('name' => '首页', 'url' => array('wechat/index')),
    array('name' => '基础设置'),
    array('name' => '关键词回复'),
);
?>
<div class="page-header">
    <h1>
        关键词回复
        <small>
            <i class="fa fa-angle-double-right"></i>
            回复管理
        </small>
    </h1>
</div>
<div class="row">
    <div class="col-sm-12">
        <div class="tabbable">
            <ul id="myTab" class="nav nav-tabs">
                <li class="active">
                    <a href="#" >
                        <i class="green icon-home bigger-110"></i>
                        文本关键词
                    </a>
                </li>

                <li class="js_loading">
                    <a href="<?php echo Yii::app()->createUrl('manager/keyWords',array('type'=>ImagetextreplayModel::IMAGE_TEXT_REPLAY_TYPE))?>" >
                        图文关键词
                    </a>
                </li>

                <li>
                    <a href="<?php echo Yii::app()->createUrl('manager/keyWordsCreate',array('type'=>TextReplayModel::TEXT_REPLAY_TYPE))?>" class="btn btn-primary js_loading">添加</a>
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
                    <th>关键词</th>
                    <th style="width: 10%">是否精准匹配</th>
                    <th class="hidden-480">回复内容</th>

                    <th></th>
                </tr>
                </thead>

                <tbody>
                <?php $page = Yii::app()->request->getParam('page',1);$i=($page-1)*Page::SIZE;foreach($data as $d){$i++;?>
                    <tr>
                        <td class="center">
                            <label>
                                <input type="checkbox" class="ace" name="id" value="<?=$d->id?>">
                                <span class="lbl"></span>
                            </label>
                        </td>

                        <td>
                            <?php foreach($d->textreplay_keywords as $keywords){?>
                            <span class="label label-sm label-primary arrowed arrowed-right"><?=$keywords->name?></span>
                            <?php }?>
                        </td>
                        <td><?php foreach($d->textreplay_keywords as $isAccurate){if($isAccurate->isAccurate){?>
                                <span class="label label-sm label-success">是</span>
                            <?php }else{?>
                                <span class="label label-sm label-warning">否</span>
                            <?php }break;}?>
                        </td>
                        <td class="hidden-480"><?=$d->content?></td>
                        <td style="width:12%">
                            <div class="visible-md visible-lg hidden-sm hidden-xs btn-group">
                                <a class="btn btn-xs btn-info js_loading" href="<?php echo Yii::app()->createUrl('manager/KeyWordsUpdate/id/'.$d->id,array('type'=>TextReplayModel::TEXT_REPLAY_TYPE))?>">
                                    <i class="fa fa-edit bigger-120">编辑</i>
                                </a>

                                <a class="btn btn-xs btn-danger  bootbox-confirm" rel="<?= Yii::app()->createUrl('manager/KeyWordsDelete/id/'.$d->id,array('type'=>TextReplayModel::TEXT_REPLAY_TYPE)) ?>">
                                    <i class="fa fa-remove bigger-120">删除</i>
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php }?>
                </tbody>
                </table>
                </div>
                </div>
            </div>
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
