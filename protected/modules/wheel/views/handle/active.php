<!DOCTYPE html>
<!-- saved from url=(0074)http://www.apiwx.com/index.php?ac=alw&c=o7MB9ji5fQRsE0ZoVAMU7SlnRyMI&tid=5 -->
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport"
          content="width=device-width,height=device-height,inital-scale=1.0,maximum-scale=1.0,user-scalable=no;">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no">
    <title>幸运大转盘抽奖</title>
    <link href="<?php echo $this->module->assetsUrl; ?>/css/activity-style.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="<?php echo $this->module->assetsUrl; ?>/js/jquery.js"></script>
    <script type="text/javascript" src="<?php echo $this->module->assetsUrl; ?>/js/jQueryRotate.2.2.js"></script>
    <script type="text/javascript" src="<?php echo $this->module->assetsUrl; ?>/js/jquery.easing.min.js"></script>
</head>
<body class="activity-lottery-winning">
<div class="main">
    <div class="outercont">
        <div id="outercont">
            <div id="outer-cont">
                <div id="outer"><img src="<?php echo $this->module->assetsUrl; ?>/images/activity-lottery-1.png"></div>
            </div>
            <div id="inner-cont">
                <div id="inner"><img src="<?php echo $this->module->assetsUrl; ?>/images/activity-lottery-2.png"></div>
            </div>
        </div>
        <div class="result">
            <div class="boxcontent boxyellow" id="result" style="display:none">
                <div class="box">
                    <div class="title-orange"><span>恭喜你中奖了</span></div>
                    <div class="Detail">

                        <p>你中了：<span class="red" id="prizetype">一等奖</span></p>

                        <p>你的兑奖码：<span class="red" id="sncode"></span></p>

                        <!--<p class="red">本次兑奖码已经关联你的微信号，你可向公众号发送 兑奖 进行查询!</p>-->
                        <div id="form">
                            <p>
                                <input name="" class="px" id="area" type="text" placeholder="游戏区">
                            </p>
                            <p>
                                <input name="" class="px" id="role" type="text" placeholder="角色名">
                            </p>
                            <p>
                                <select name="banben" class='px' id="banben">
                                    <option value="android">android</option>
                                    <option value="ios+">ios正版</option>
                                    <option value="ios-">ios越狱</option>
                                </select>
                            </p>
                            <input type="hidden" id="encryption">
                            <p>
                                <input class="pxbtn" id="save-btn" name="提 交" type="button" value="提 交">
                            </p>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>


</div>
<script type="text/javascript">
    $(function () {
        var lostDeg = [0, 130];
        var prizeDeg = [220,170,310,90,40];//50经验礼包,90，270传奇卷轴,130元宝500，180 中级星石，320兽魂礼包
        var prize = <?php echo $prize?>==0?null:<?php echo $prize?>;
        var count = <?php echo $hasCount?>;
        var totalCount = <?php echo $totalCount?>;
        var outter, inner, timer, running = false;
        $("#inner").rotate({
            bind:{
                click:function(){
                    if (running)
                        return;
                    if (count >=totalCount) {
                        alert("您已经抽了 " + count + "次奖。明天再来吧!");
                        return
                    }
                    if (prize != null) {
                        alert("亲，你不能再参加本次活动了喔！下次再来吧~");
                        return
                    }
                    var a = 0;//40 兽魂礼包 90传奇抽奖券  130 谢谢参与 170 中级星级石 220 元宝 310 经验礼包 0谢谢参与
                    $.ajax({
                        url: "<?php echo Yii::app()->createUrl('wheel/handle/active')?>",
                        dataType: "json",
                        async: false,
                        data: {
                            encryption:'<?php echo $encryption?>',
                            t: Math.random()
                        },
                        beforeSend: function () {
                            running = true;
                        },
                        success: function (data) {
                            if (data.error == "invalid") {
                                alert("您已经抽了 "+totalCount+" 次奖。明天再来吧");
                                count = 3;
                                return
                            }
                            if (data.success) {
                                prize = data.prizetype;
                                sncode = data.sn;
                                name = data.name;
                                a = prizeDeg[data.prizetype - 1];
                                $("#prizetype").text(name);
                                if(sncode){
                                    $("#form").html('');
                                }
                                $("#encryption").val(data.encryption);
                            }
                            running = false;
                            count++;
                        },
                        error: function () {
                            prize = null;
                            running = false;
                            count++
                        },
                        timeout: 4000
                    })
                    $(this).rotate({
                        duration:3000,
                        angle: 0,
                        animateTo:1440+a,
                        easing: $.easing.easeOutSine,
                        callback: function(){
                            running = false;
                            if(a>0){
                                $("#sncode").text(sncode);
                                $("#result").slideToggle(500);
                                $("#outercont").slideUp(500)
                            }else{
                                alert('谢谢您的参与，下次再接再厉');
                            }
                        }
                    });
                }
            }
        });
    });
    $("#save-btn").bind("click", function () {
        var url = "<?php echo Yii::app()->createUrl('wheel/handle/save')?>";
        var btn = $(this);
        var submitData = {
            area: $("#area").val(),
            role: $("#role").val(),
            banben: $("#banben").val(),
            encryption:$("#encryption").val()
        };
        $.post(url, submitData, function (data) {
            if (data.success == true) {
                alert("提交成功，谢谢您的参与");
                return
            } else {
            }
        }, "json")
    });

</script>


</body>
</html>