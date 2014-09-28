<div class="row">
    <div class="col-xs-12">
        <?php $form = $this->beginWidget('CActiveForm', array(
            'id' => 'group-form',
            'enableAjaxValidation' => false,
            'enableClientValidation' => true,
            'focus' => array($model, 'content'),
            'htmlOptions' => array('class' => 'form-horizontal')
        )); ?>
        <?php echo $form->errorSummary($model, BootStrapUI::alertError, '', array('class' => BootStrapUI::alertErrorClass)); ?>

        <table cellspacing="0" cellpadding="0" width="100%" border="0">
            <tbody>
            <tr>
                <td height="5"></td>
                <td></td>
            </tr>
            <tr>
                <td width="420" valign="top">
                    <?php echo $form->textArea($model,'content',array('style'=>'width:420px; height:500px; margin:5px 0'))?>

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
                        13.藏头诗 藏头诗+关键词&#12288;例：藏头诗我爱你&#12288;<br>
                        14.笑话&#12288;直接发送笑话<br>
                        15.糗事&#12288;直接发送糗事<br>
                        16.快递 快递＋快递名＋快递号&#12288;例：快递顺丰117215889174<br>
                        17.健康指数查询&#12288;健康＋高，＋重&#12288;例：健康170,65<br>
                        18.朗读 朗读＋关键词&#12288;例：朗读微旭｜微信营销专家多用户营销系统<br>
                        19.计算器 计算器使用方法&#12288;例：计算50-50&#12288;，计算100*100<br>
                        20.输入价格了解微旭｜微信营销专家平台系统的价格<br>
                        21.输入服务了解微旭｜微信营销专家平台系统的售后服务<br>
                        23.输入抽奖，即可玩幸运大抽奖<br>
                        2４.输入会员即可填写会员资料<br>
                        25.更多功能请回复帮助，或者help<br>

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