<!doctype html>
<html lang="zh-cn">
<head>
    <meta charset="utf-8">
    <meta name="Description" content="" />
    <meta name="Keywords" content="" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta content="telephone=no" name="format-detection">
    <title>签到有奖</title>
    <style>
        body,ul,ol,p,h1,h2,h3,div,table,tr,td{margin:0;padding:0;}
        a,button,input{-webkit-tap-highlight-color:rgba(255,0,0,0);}
        ol,ul{list-style:none;}
        .hide{width:0;height:0;overflow:hidden;text-indent:-9999px;display:none;}
        body{background:#f4efe6;font-size:14px; color:#7F7010;}
        .clearfix:after{content:" "; clear:both; height:0; visibility:hidden; display:block;}
        .clearfix{*zoom:1;}
        img{width:100%;height:auto;display:block;border:0;}
        .wrap{width:100%;background:url("<?php echo $this->module->assetsUrl; ?>/images/background.jpg") repeat-y;height: }
        .cjlist,.cjlist img,.box,.rili,.rili img,.qdlist,.qdlist img,.inf,.ibox,.anlist,.anlist img,.tbox,.tc3 img{margin:0 auto;}
        .box{ height: auto;
            display: none;
            left: 8%;
            position: absolute;
            width: 84.875%;}
        .tip,.cjlist p,.rili li,.qdlist p,.jies{text-align:center;}
        .cjlist,.qdlist{width:95%; padding-top:5px;}
        .qdlist{ padding-top:0;}
        .cjlist li,.qdlist li{width:33.33%; position:relative;}
        .rili li,.cjlist li,.qdlist li,.anlist li,.inf strong,.inf span{float:left;}
        .cjlist p,.qdlist p{ position:absolute; width:100%; top:75%; left:0;}
        .cjlist img,.qdlist img{ width:94.41%; padding:5px 0;}
        .qdlist img{padding:0;}
        .rili{ width:93.70%;}
        .rili li{width:14.28%; position:relative;}
        .rili strong{ display:block; font-size:1em;}
        .rili strong.coy{color:#ffe700;}
        .rili img{ width:98%;}
        .rili p{ position:absolute; width:100%; left:0; top:15%; color:#840C0D;}
        .qdlist p{ top:55%;}
        .qdlist strong{ color:#fff;}
        .inf{ width:96%; color:#c49dfa; padding-top:5px;}
        .inf strong{ width:15%; text-align:right;}
        .inf span{ width:85%;}
        .ibox{color:#c49dfa; width:92%; padding:10px 0;}
        .ibox h2{ font-size:1.4em; padding-bottom:5px;}
        .ibox li{ position:relative; padding-left:14px; line-height:16px;}
        .ibox strong{ position:absolute; top:0; left:0;}
        .jies{ color:#8b47ea; background:#250351; padding:5px 0; font-size:12px;}
        .tc1{ width:315px; height:184px;}
        .tc{ width:96%; border:2px solid #3e0000; background:#F4EFE6; padding:40px 0; position:relative;}
        .tc2{text-align:center;}
        .anlist{ width:65%; padding-top:15px;}
        .anlist li{ width:50%;}
        .anlist img{ width:89%;}
        .tbox{ width:80%;}
        .tbox table{ width:100%; margin-top:2px;}
        .tbox th{ width:30%; text-align:right;font-weight:normal;}
        .tbox td{ width:70%;}
        .tbox input{ background:#ac70ff; color:#fff; border:none; padding:0 2px; width:100%;}
        .tc3 img{ width:40%; margin-top:15px;}
        .guanbi{ position:absolute; width:40px; height:40px; background:#3e0000; color:#fff; display:block; line-height:36px; text-align:center; font-size:24px; font-weight:bold; text-decoration:none; top:0; right:0; font-family:Arial, Helvetica, sans-serif;}
        .fxbox{ position:relative;}
        .qiu{ position:absolute; width:37.5%; top:31%; right:50%;}
        .tbox1{ width:90%;}
        .tbox1 th{ width:42%;}
        .tbox1 td{ width:58%;}
        .c_item{height:28px;width:100%;position: relative; margin-bottom:5px;}
        .c_item input{text-indent: 3px;}
        .c_txt{border-radius:5px;height:25px;border: 1px solid #d0d4d9;width: 100%;color: #b7b7b7;font-weight: bold;box-shadow: 0 4px 6px #f1f1f1 inset; -webkit-appearance:none;background: #fff;position: relative;}
        .c_txt select{height:22px;border: 0 none;width: 100%;border: 0 none;margin-top: 4px;color: #b7b7b7;font-weight: bold;-webkit-appearance:none;position: relative;z-index: 2;background: none;}
        .c_txt option{height:22px;line-height: 24px;}
        .c_txt2{width: 45%;display:block;float: left;}
        .c_down{background:-webkit-gradient(linear, left top, left bottom, color-stop(0.05, #aa8cf9), color-stop(1, #8261f6));background:-moz-linear-gradient(top, #aa8cf9 5%, #8261f6 100%);background:-webkit-linear-gradient(top, #aa8cf9 5%, #8261f6 100%);background:-o-linear-gradient(top, #aa8cf9 5%, #8261f6 100%);background:linear-gradient(to bottom, #aa8cf9 5%, #8261f6 100%);background-color:#4fb6fb;-moz-border-top-right-radius:6px;-moz-border-bottom-right-radius:6px;-webkit-border-top-right-radius:6px;-webkit-border-top-bottom-radius:6px;border-top-right-radius:6px;border-bottom-right-radius:6px;border:1px solid #8261f6;display:block;cursor:pointer;color:#2270b9;font-family:arial;font-size:15px;font-weight:bold;text-decoration:none;text-shadow:0 1px 0 #af7cde;width:30px;height:25px;position: absolute;right:-1px;top:-1px;z-index: 1;}
        .c_down b{width:0; height:0; border-left:10px solid transparent;border-right:10px solid transparent;border-top:10px solid #fff;position:absolute;top:9px;left:6px;}
        .tc1,.tc{ display:none;}
        .tc1{ width:100%; height:100%; position:relative;}
        .tc1 img{ width:100%; position:absolute; top:0; left:0;}
    </style>
</head>
<body>
<div class="wrap">
    <img src="<?php echo $this->module->assetsUrl; ?>/images/header.jpg" alt="签到有奖">
    <img src="<?php echo $this->module->assetsUrl; ?>/images/dateTimeHeader.jpg" id="dateHeader">
    <img src="<?php echo $this->module->assetsUrl; ?>/images/datetTimeContent.jpg" id="dateContent">
    <div class="box">
        <ul class="rili clearfix">
            <?php foreach($date as $d){?>
                <li id="<?php echo $d['m'].$d['d']?>">
                    <?php if($d['m']==0){?>
                        <img src="<?php echo $this->module->assetsUrl; ?>/images/dateBgBlack.png">
                        <p><strong></strong></p>
                    <?php }else{?>
                        <?php if ($d['isSin']) { ?>
                            <img src="<?php echo $this->module->assetsUrl; ?>/images/dateSined.png">
                        <?php } else { ?>
                            <img src="<?php echo $this->module->assetsUrl; ?>/images/dateBg.png">
                        <?php } ?>
                        <p><strong><?php echo $d['d']?></strong></p>
                    <?php }?>
                </li>
            <?php }?>
        </ul>
    </div>
    <a href="javascript:void(0);" id="btnQianDao"><img src="<?php echo $this->module->assetsUrl; ?>/images/button.jpg"></a>
    <img src="<?php echo $this->module->assetsUrl; ?>/images/footer.jpg">
</div>
<!--弹窗1-->
<div class="tc1" id="tc1">
    <img src="<?php echo $this->module->assetsUrl; ?>/images/fx.png">
</div>

<div class="tc tc2" id="tc6">
    <a href="javascript:void(0);" class="guanbi">x</a>
    <strong></strong>
</div>
</body>

<script type="text/javascript" src="<?php echo $this->module->assetsUrl; ?>/js/zepto.js"></script>
<script type="text/javascript" src="<?php echo $this->module->assetsUrl; ?>/js/milo.js"></script>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script type="text/javascript">
    window.alert = function(sText){
        $("#tc6 strong").html(sText);
        TGDialogS('tc6');
    }
    milo.ready(function(){
        $(".box").show().css('top',$("#dateContent").offset().top +"px");
        <?php if($prize==0){?>
        alert('活动已结束');
        <?php }?>
        $("#btnQianDao").click(function(){
            //签到活动
            //TGDialogS('tc2');
            $.ajax({
                url: "<?php echo Yii::app()->createUrl('registration/handle/active')?>",
                dataType: "json",
                async: false,
                data: {
                    encryption: '<?php echo $encryption?>',
                    t: Math.random()
                },
                success: function (data) {
                    if(data.success){
                        alert(data.msg);
                        if(data.sinDate){
                            var obj = $("#"+data.sinDate);
                            if(obj){
                                obj.find('img').attr('src','<?php echo$this->module->assetsUrl; ?>/images/dateSined.png');
                            }
                        }
                    }
                },
                error: function () {
                    prize = null;
                },
                timeout: 4000
            })
        });
        //绑定按钮事件
        $(".guanbi, #btnlotteryno1, #btnlotteryno2, #btnCancelfloat").on('click', function(){
            closeDialog();
        });

        /*分享按钮绑定*/
        $("#wxFenxiang").on('click', function(){
            TGDialogS('tc1');
        });
        $("#tc1").on('click', function(){
            closeDialog();
        });
    });

    function TGDialogS(e){
        need("biz.dialog",function(Dialog){
            Dialog.show({
                id:e,
                bgcolor:'#000', //弹出“遮罩”的颜色，格式为"#FF6600"，可修改，默认为"#fff"
                opacity:50      //弹出“遮罩”的透明度，格式为｛10-100｝，可选
            });
        });
    }

    function closeDialog(){
        need("biz.dialog",function(Dialog){
            Dialog.hide();
        });
    }

    //微信分享
    wx.config({
        debug: false,
        appId: '<?php echo $appId?>',
        timestamp: <?php echo $timestamp?>,
        nonceStr: '<?php echo $nonceStr?>',
        signature: '<?php echo $signature?>',
        jsApiList: [
            'checkJsApi',
            'onMenuShareTimeline',
            'onMenuShareAppMessage',
            'onMenuShareQQ',
            'onMenuShareWeibo',
            'hideMenuItems',
            'showMenuItems',
            'hideAllNonBaseMenuItem',
            'showAllNonBaseMenuItem',
            'translateVoice',
            'startRecord',
            'stopRecord',
            'onRecordEnd',
            'playVoice',
            'pauseVoice',
            'stopVoice',
            'uploadVoice',
            'downloadVoice',
            'chooseImage',
            'previewImage',
            'uploadImage',
            'downloadImage',
            'getNetworkType',
            'openLocation',
            'getLocation',
            'hideOptionMenu',
            'showOptionMenu',
            'closeWindow',
            'scanQRCode',
            'chooseWXPay',
            'openProductSpecificView',
            'addCard',
            'chooseCard',
            'openCard'
        ]
    });

    wx.ready(function () {
        // 1 判断当前版本是否支持指定 JS 接口，支持批量判断
        document.querySelector('#checkJsApi').onclick = function () {
            wx.checkJsApi({
                jsApiList: [
                    'getNetworkType',
                    'previewImage'
                ],
                success: function (res) {
                    alert(JSON.stringify(res));
                }
            });
        };
        // 2. 分享接口
        // 2.1 监听“分享给朋友”，按钮点击、自定义分享内容及分享结果接口
        document.querySelector('#onMenuShareAppMessage').onclick = function () {
            wx.onMenuShareAppMessage({
                title: '互联网之子',
                desc: '在长大的过程中，我才慢慢发现，我身边的所有事，别人跟我说的所有事，那些所谓本来如此，注定如此的事，它们其实没有非得如此，事情是可以改变的。更重要的是，有些事既然错了，那就该做出改变。',
                link: 'http://movie.douban.com/subject/25785114/',
                imgUrl: 'http://demo.open.weixin.qq.com/jssdk/images/p2166127561.jpg',
                trigger: function (res) {
                    alert('用户点击发送给朋友');
                },
                success: function (res) {
                    alert('已分享');
                },
                cancel: function (res) {
                    alert('已取消');
                },
                fail: function (res) {
                    alert(JSON.stringify(res));
                }
            });
            alert('已注册获取“发送给朋友”状态事件');
        };
        // 2.2 监听“分享到朋友圈”按钮点击、自定义分享内容及分享结果接口
        document.querySelector('#onMenuShareTimeline').onclick = function () {
            wx.onMenuShareTimeline({
                title: '互联网之子',
                link: 'http://movie.douban.com/subject/25785114/',
                imgUrl: 'http://demo.open.weixin.qq.com/jssdk/images/p2166127561.jpg',
                trigger: function (res) {
                    alert('用户点击分享到朋友圈');
                },
                success: function (res) {
                    alert('已分享');
                },
                cancel: function (res) {
                    alert('已取消');
                },
                fail: function (res) {
                    alert(JSON.stringify(res));
                }
            });
            alert('已注册获取“分享到朋友圈”状态事件');
        };
        // 2.3 监听“分享到QQ”按钮点击、自定义分享内容及分享结果接口
        document.querySelector('#onMenuShareQQ').onclick = function () {
            wx.onMenuShareQQ({
                title: '互联网之子',
                desc: '在长大的过程中，我才慢慢发现，我身边的所有事，别人跟我说的所有事，那些所谓本来如此，注定如此的事，它们其实没有非得如此，事情是可以改变的。更重要的是，有些事既然错了，那就该做出改变。',
                link: 'http://movie.douban.com/subject/25785114/',
                imgUrl: 'http://img3.douban.com/view/movie_poster_cover/spst/public/p2166127561.jpg',
                trigger: function (res) {
                    alert('用户点击分享到QQ');
                },
                complete: function (res) {
                    alert(JSON.stringify(res));
                },
                success: function (res) {
                    alert('已分享');
                },
                cancel: function (res) {
                    alert('已取消');
                },
                fail: function (res) {
                    alert(JSON.stringify(res));
                }
            });
            alert('已注册获取“分享到 QQ”状态事件');
        };
        // 2.4 监听“分享到微博”按钮点击、自定义分享内容及分享结果接口
        document.querySelector('#onMenuShareWeibo').onclick = function () {
            wx.onMenuShareWeibo({
                title: '互联网之子',
                desc: '在长大的过程中，我才慢慢发现，我身边的所有事，别人跟我说的所有事，那些所谓本来如此，注定如此的事，它们其实没有非得如此，事情是可以改变的。更重要的是，有些事既然错了，那就该做出改变。',
                link: 'http://movie.douban.com/subject/25785114/',
                imgUrl: 'http://img3.douban.com/view/movie_poster_cover/spst/public/p2166127561.jpg',
                trigger: function (res) {
                    alert('用户点击分享到微博');
                },
                complete: function (res) {
                    alert(JSON.stringify(res));
                },
                success: function (res) {
                    alert('已分享');
                },
                cancel: function (res) {
                    alert('已取消');
                },
                fail: function (res) {
                    alert(JSON.stringify(res));
                }
            });
            alert('已注册获取“分享到微博”状态事件');
        };
    })

</script>



</html><!--[if !IE]>|xGv00|9eb89285ba697914bba3790392525b02<![endif]-->