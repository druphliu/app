<div class="row">
    <div class="col-xs-12">
        <?php $form = $this->beginWidget('CActiveForm', array(
            'id' => 'group-form',
            'enableAjaxValidation' => false,
            'enableClientValidation' => true,
            'focus' => array($model, 'content'),
            'htmlOptions' => array('class' => 'form-horizontal'),
            'clientOptions'=>array(
                'validateOnSubmit'=>true,
                'afterValidate' => 'js:function(form, data, hasError) {
                  if(hasError) {
                      for(var i in data) $("#"+i).parents(".form-group").addClass("has-error");
                      return false;
                  }
                  else {
                      form.children().removeClass("has-error");
                      return true;
                  }
              }',
                'afterValidateAttribute' => 'js:function(form, attribute, data, hasError) {
                  if(hasError) $("#"+attribute.id).parents(".form-group").addClass("has-error");
                      else $("#"+attribute.id).parents(".form-group").removeClass("has-error");
              }'
            )
        )); ?>
        <?php echo $form->errorSummary($model, BootStrapUI::alertError, '', array('class' => BootStrapUI::alertErrorClass)); ?>

        <table cellspacing="0" cellpadding="0" width="100%" border="0">
            <tbody>
            <tr>
                <td height="5"></td>
                <td></td>
            </tr>
            <tr>
                <td width="520" valign="top">
                    <div class="wysiwyg-editor" id="editor"><?php echo $model->content?></div>
                    <?php echo $form->hiddenField($model,'content',array('id'=>'content'))?>
                </td>
                <td valign="top">
                    <div style="margin-left:20px" class="zdhuifu">
                        <h4>参考范例：</h4>
                        1.附近周边信息查询lbs<br>
                        2.音乐查询&#12288;音乐＋音乐名 例：音乐爱你一万年<br>
                        3.天气查询&#12288;城市名＋天气&#12288;例上海天气<br>
                        4.手机归属地查询(吉凶 运势) 手机＋手机号码&#12288;例：手机13917778912<br>
                        5.身份证查询&#12288;身份证＋号码&#12288;&#12288;例：身份证342423198803015568<br>
                        6.公交查询&#12288;公交＋城市＋公交编号&#12288;例：上海公交774<br>
                        7.火车查询&#12288;火车＋城市＋目的地&#12288;例火车上海南京<br>
                        8.翻译 支持 及时翻译，语音翻译&#12288;翻译＋关键词 例：翻译你好<br>
                        9.彩票查询&#12288;彩票＋彩票名 例如:彩票双色球<br>
                        10.周公解梦&#12288;梦见+关键词&#12288;例如:梦见父母<br>
                        11.陪聊&#12288;直接输入聊天关键词即可<br>
                        12.聊天&#12288;直接输入聊天关键词即可<br>

                    </div>
                </td>
            </tr>
            <tr>
                <td height="50">

                    <?php echo BootStrapUI::saveButton(); ?>
                    &nbsp; &nbsp; &nbsp;
                    <?php echo BootStrapUI::resetButton(); ?>
                    &nbsp; &nbsp; &nbsp;
                    <a class="btn btn-primary" href="<?php echo Yii::app()->createUrl('manager/subscribeReplay',array('type'=>ImagetextreplayModel::IMAGE_TEXT_REPLAY_TYPE))?>">切换到图文模式</a>

                </td>
                <td valign="top">
                </td>
            </tr>
            </tbody>
        </table>
        <?php $this->endWidget(); ?>
    </div>
</div>
<!-- inline scripts related to this page -->
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/jquery-ui-1.10.3.custom.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/jquery.ui.touch-punch.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/markdown/markdown.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/markdown/bootstrap-markdown.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/jquery.hotkeys.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/bootstrap-wysiwyg.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/bootbox.min.js"></script>
<script>
    $().ready(function(){
        $('#editor').ace_wysiwyg({
            toolbar:
                [
                    {name:'createLink', className:'btn-pink'},
                    {name:'unlink', className:'btn-pink'}
                ]
        }).prev().addClass('wysiwyg-style2');
    });
    $("#submit").click(function(){
        $("#content").val($("#editor").html());
    })
</script>