<?php
/* @var $this GroupController */

$this->breadcrumbs = array(
    array('name' => '首页', 'url' => array('wechat/index')),
    array('name' => '营销管理'),
    array('name' => '彩蛋'),
);
?>
<div class="page-header">
    <h1>
        刮刮乐
        <small>
            <i class="fa fa-angle-double-right"></i>
            添加彩蛋
        </small>
    </h1>
</div>
<?php $this->renderPartial('_form', array('model' => $model,'type'=>$type,'wechatId'=>$wechatId,'responseId'=>$responseId,'menuList'=>$menuList));
?>
