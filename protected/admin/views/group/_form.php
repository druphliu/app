<div class="row">
    <div class="col-xs-12">
        <?php $form = $this->beginWidget('CActiveForm', array(
            'id' => 'group-form',
            'enableAjaxValidation' => false,
            'enableClientValidation' => true,
            'focus' => array($model, 'name'),
            'htmlOptions' => array('class' => 'form-horizontal')
        )); ?>
        <?php echo $form->errorSummary($model, BootStrapUI::alertError, '', array('class' => BootStrapUI::alertErrorClass)); ?>

        <div class="form-group">
            <?php echo $form->labelEx($model, 'name', array('class' => BootStrapUI::formLabelClass)); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model, 'name', array('class' => 'col-xs-8 col-sm-2')); ?>
                <?php echo $form->error($model, 'name', array('class' => 'help-block col-xs-12 col-sm-reset inline')); ?>
            </div>
        </div>
        <div class="space-4"></div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'action', array('class' => BootStrapUI::formLabelClass)); ?>
            <div class="col-sm-9">
                <?php $actions = explode(',', $model->action);
                foreach (MemberMenu::$menuList as $name => $menu) {
                    ?>
                <fieldset style="margin:30px; border-radius:5px">
                <legend>
                    <?php echo Chtml::checkBox($menu['act'], in_array($menu['act'], $actions),
                        array('class' => 'mainGroup', 'rel' => $menu['act'], 'value' => $menu['act'])); ?>
                    <label for="<?= $menu['act'] ?>"><?php echo $name ?></label>
                </legend>
                    <?php foreach ($menu['action'] as $k => $action) { ?>
                        <?php echo Chtml::checkBox($action['act'], in_array($action['act'], $actions), array('rel' => $menu['act'], 'class' => 'sub_' . $menu['act'] . " subGroup", 'value' => $action['act'])); ?>
                        <label for="<?= $action['act'] ?>"><?php echo $action['name'] ?></label>
                    <?php } ?>
                    </fieldset>
                <?php } ?>
                <?php echo $form->hiddenField($model, 'action'); ?>
                <?php echo $form->error($model, 'action'); ?>
            </div>
        </div>
        <div class="clearfix form-actions">
            <div class="col-md-offset-3 col-md-9">
                <?php echo BootStrapUI::saveButton(); ?>
                &nbsp; &nbsp; &nbsp;
                <?php echo BootStrapUI::resetButton(); ?>
            </div>
        </div>

        <?php $this->endWidget(); ?>
    </div>
</div>
<script>
    $().ready(function () {
        $(".subGroup").click(function () {
            var id = $(this).attr('rel');
            if ($(this).attr('checked') == 'checked') {
                $(".mainGroup").each(function () {
                    if ($(this).attr("rel") == id && $(this).attr("checked") != "checked") {
                        $(this).attr("checked", "checked");
                        handleData($(this).val(), true);//添加父菜单
                    }
                });
                handleData($(this).val(), true);//添加当前子菜单
            } else {
                //子菜单无选择则父菜单取消选择状态
                var isChecked = false;
                $(this).siblings(".subGroup").each(function () {
                    if ($(this).attr("checked") == "checked") {
                        isChecked = true;
                    }
                });
                if (isChecked == false) {
                    $(".mainGroup").each(function () {
                        if ($(this).attr("rel") == id) {
                            $(this).removeAttr("checked");
                            handleData($(this).val(), false);//去掉父菜单
                        }
                    });
                }
                handleData($(this).val(), false);//去掉当前子菜单
            }
        });
        $(".mainGroup").click(function () {
            var id = $(this).attr('rel');
            var isChecked = false;
            var isAdd = false;
            if ($(this).attr('checked') == 'checked') {
                isChecked = true;
                isAdd = true;
            }
            handleData($(this).val(), isAdd);//添加(删除)父菜单
            $(".sub_" + id).each(function () {
                isChecked == true ? $(this).attr("checked", "checked") : $(this).removeAttr("checked");
                handleData($(this).val(), isAdd);
            })
        });
        //数据封装
        function handleData(data, isAdd=true) {
            var nameData = $("#GroupModel_action").val();
            var arrayData = new Array();
            arrayData = nameData ? nameData.split(",") : arrayData;
            if (isAdd == true) {
                arrayData.push(data);
            } else {
                arrayData.splice($.inArray(data, arrayData), 1);
            }
            $("#GroupModel_action").val(arrayData.join(','));
        }
    })
</script>