<div class="row">
    <div class="col-xs-12">
        <?php $form = $this->beginWidget('CActiveForm', array(
            'id' => 'group-form',
            'enableAjaxValidation' => false,
            'enableClientValidation' => true,
            'focus' => array($model, 'keyWords'),
            'htmlOptions' => array('class' => 'form-horizontal')
        )); ?>
        <?php echo $form->errorSummary($model, BootStrapUI::alertError, '', array('class' => BootStrapUI::alertErrorClass)); ?>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'title', array('class' => BootStrapUI::formLabelClass)); ?>
            <div class="col-sm-4">
                <?php echo $form->textField($model, 'title', array('class' => 'col-xs-10 col-sm-10')); ?>
                <?php echo $form->error($model, 'title', array('class' => 'help-block col-xs-12 col-sm-reset inline')); ?>
            </div>
        </div>
        <div class="space-4"></div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'type', array('class' => BootStrapUI::formLabelClass)); ?>
            <div class="col-sm-4">
                <?php echo $form->dropDownList($model, 'type', GiftModel::$typeArray, array('class' => 'col-xs-3 col-sm-3')); ?>
                <?php echo $form->error($model, 'type', array('class' => 'help-block col-xs-12 col-sm-reset inline')); ?>
            </div>
        </div>

        <div class="space-4"></div>
        <div id="keyword" <?php if ($type != GiftModel::TYPE_KEYWORDS){ ?>style="display: none" <?php } ?>>
            <div class="form-group">
                <?php echo $form->labelEx($model, 'keyword', array('class' => BootStrapUI::formLabelClass)); ?>
                <div class="col-sm-4">
                    <?php echo $form->textField($model, 'keyword', array('class' => 'col-xs-10 col-sm-10')); ?>
                    <?php echo $form->error($model, 'keyword', array('class' => 'help-block col-xs-12 col-sm-reset inline')); ?>
                </div>
            </div>
            <div class="space-4"></div>
            <div class="form-group">
                <?php echo $form->labelEx($model, 'isAccurate', array('class' => BootStrapUI::formLabelClass)); ?>
                <div class="col-sm-4">
                    <?php echo $form->checkBox($model, 'isAccurate', array('class' => 'col-xs-2 col-sm-2')); ?>
                    <?php echo $form->error($model, 'isAccurate', array('class' => 'help-block col-xs-12 col-sm-reset inline')); ?>
                </div>
            </div>
        </div>
        <div class="form-group" id="action"
             <?php if ($type != GiftModel::TYPE_MENU){ ?>style="display: none" <?php } ?>>
            <?php echo $form->labelEx($model, 'action', array('class' => BootStrapUI::formLabelClass)); ?>
            <div class="col-sm-4">
                <?php echo $form->textField($model, 'action', array('class' => 'col-xs-10 col-sm-10')); ?>
                <?php echo $form->error($model, 'action', array('class' => 'help-block col-xs-12 col-sm-reset inline')); ?>
            </div>
        </div>
        <div class="space-4"></div>
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
        $("#GiftModel_type").change(function () {
            var type = $(this).val();
            var TYPE_KEYWORDS = "<?= GiftModel::TYPE_KEYWORDS?>";
            var TYPE_MENU = "<?= GiftModel::TYPE_MENU?>";
            switch (type) {
                case TYPE_KEYWORDS:
                    $("#keyword").show();
                    $("#action").hide();
                    break;
                case TYPE_MENU:
                    $("#keyword").hide();
                    $("#action").show();
                    break;
            }
        })
    })
</script>