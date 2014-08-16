<div class="row">
    <div class="col-xs-12">
        <?php $form = $this->beginWidget('CActiveForm', array(
            'id' => 'group-form',
            'enableAjaxValidation' => false,
            'enableClientValidation' => true,
            'focus' => array($model, 'nickname'),
            'htmlOptions' => array('class' => 'form-horizontal')
        )); ?>
        <?php echo $form->errorSummary($model, BootStrapUI::alertError, '', array('class' => BootStrapUI::alertErrorClass)); ?>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'paymentOption', array('class' => BootStrapUI::formLabelClass)); ?>
            <div class="col-sm-9">
                <?php echo $form->dropDownList($model, 'payCode', $priceList, array('class' => 'col-xs-2 col-sm-2')); ?>
                <?php echo $form->dropDownList($model, 'count', $mouth, array('class' => 'col-xs-2 col-sm-2')); ?>
                <?php echo $form->error($model, 'payCode', array('class' => 'help-block col-xs-12 col-sm-reset inline')); ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'amount', array('class' => BootStrapUI::formLabelClass)); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model, 'amount', array('class' => 'col-xs-8 col-sm-2')); ?>
                <?php echo $form->error($model, 'amount', array('class' => 'help-block col-xs-12 col-sm-reset inline')); ?>
            </div>
        </div>
        <div class="space-4"></div>
        <div class="clearfix form-actions">
            <div class="col-md-offset-3 col-md-9">
                <?php echo BootStrapUI::ackButton(); ?>
                &nbsp; &nbsp; &nbsp;
                <?php echo BootStrapUI::resetButton(); ?>
            </div>
        </div>

        <?php $this->endWidget(); ?>
    </div>
</div>
<script>
    $().ready(function () {
        $("#VipPaymentModel_payCode").change(function(){
            getAmount();
        });
        $("#VipPaymentModel_count").change(function(){
            getAmount();
        })
    })
    function getAmount(){
        price = $("#VipPaymentModel_payCode").val();
        count = $("#VipPaymentModel_count").val();
        amount = parseInt(price)*parseInt(count);
        $("#VipPaymentModel_amount").val(amount);
    }
</script>