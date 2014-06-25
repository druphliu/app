<?php
/* @var $this GroupController */

$this->breadcrumbs = array(
    'Group' => array('/group'),
    'Create',
);
?>
<h1><?php echo $this->id . '/' . $this->action->id; ?></h1>
<div class="form">


    <?php $form = $this->beginWidget('CActiveForm', array(
        'id' => 'group-form',
        'enableAjaxValidation' => true,
        'enableClientValidation' => true,
        'focus' => array($model, 'name'),
    )); ?>

    <?php echo $form->errorSummary($model); ?>

    <div class="row">
        <?php echo $form->labelEx($model, 'name'); ?>
        <?php echo $form->textField($model, 'name'); ?>
        <?php echo $form->error($model, 'name'); ?>
    </div>
    <div class="row">
        <?php echo $form->labelEx($model, 'action'); ?>
            <?php foreach(AdminMenu::$menuList as $name=>$menu){?>
                <?php echo Chtml::checkBox($menu['act'],false,array('class'=>'mainGroup','rel'=>$menu['act']));?>
				<label for="<?=$menu['act']?>"><?php echo $name ?></label>
                <?php foreach($menu['action'] as $k=>$action){?>
                    <?php echo Chtml::checkBox($action['act'],false,array('rel'=>$menu['act'],'class'=>'sub_'.$menu['act']." sub_group"));?>
					<label for="<?=$action['act']?>"><?php echo $action['name']?></label>
                <?php }?>
            <?php }?>
        <?php echo $form->hiddenField($model, 'action'); ?>
        <?php echo $form->error($model, 'action'); ?>
    </div>
    <div class="row submit">
        <?php echo CHtml::submitButton('Login'); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->
<p>
    You may change the content of this page by modifying
    the file <tt><?php echo __FILE__; ?></tt>.
</p>
<script>
$().ready(function(){
	$(".sub_group").click(function(){
		var id = $(this).attr('rel');
		if($(this).attr('checked')=='checked'){
			$(".mainGroup").each(function(){
				if($(this).attr("rel") == id && $(this).attr("checked")!="checked"){
					$(this).attr("checked","checked");
					handleData($(this).val(),true);//添加当前子菜单
				}
			});
		}else{
		//子菜单无选择则父菜单取消选择状态
			var isChecked = false;
			$(this).siblings(".sub_group").each(function(){
				if($(this).attr("checked")=="checked"){
					isChecked = true;
				}
			});
			if(isChecked == false){
				$(".mainGroup").each(function(){
					if($(this).attr("rel") == id){
						$(this).removeAttr("checked");
						handleData($(this).val(),false);//去掉父菜单
							}
					});
			}
			handleData($(this).val(),false);//去掉当前子菜单
		}
	});
	$(".mainGroup").click(function(){
		var id = $(this).attr('rel');
		var isChecked = false;
		var isAdd = false;
		if($(this).attr('checked')=='checked'){
			isChecked = true;
			isAdd = true;	
		}
		handleData($(this).val(),isAdd);//添加(删除)父菜单
		$(".sub_"+id).each(function(){
				isChecked==true?$(this).attr("checked","checked"):$(this).removeAttr("checked");
				handleData($(this).val(),isAdd);
			})
	});
	//数据封装
	function handleData(data,isAdd=true){
		var nameData = $("#GroupModel_action").val();
		var arrayData = new Array();
		arrayData = nameData?nameData.split(","):arrayData;
		if(isAdd==true){
			arrayData.push(data);
		}else{
			arrayData.splice($.inArray(data,arrayData),1);
		}
		$("#GroupModel_action").val(arrayData.join(','));
	}
})
</script>