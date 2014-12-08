<?php
/* @var $this GroupController */

$this->breadcrumbs = array(
    array('name' => '首页', 'url' => array('wechat/index')),
    array('name' => '外接平台管理', 'url' => array('open/index')),
    array('name' => '平台管理'),
);
?>
<div class="page-header">
    <h1>
        外接平台管理
        <small>
            <i class="fa fa-angle-double-right"></i>
            添加平台
        </small>
    </h1>
</div>
<?php $this->renderPartial('_form', array('model' => $model));
?>
