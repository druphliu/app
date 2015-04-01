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
    <title>砸金蛋</title>
    <link href="<?php echo $this->module->assetsUrl; ?>/css/activity-style.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="<?php echo $this->module->assetsUrl; ?>/js/jquery.js"></script>
</head>
<body class="activity-lottery-winning">

<div class="egg">
    <ul class="eggList">
        <p class="hammer" id="hammer">锤子</p>
        <p class="resultTip" id="resultTip"><b id="result"></b></p>
        <li><span>1</span><sup></sup></li>
        <li><span>2</span><sup></sup></li>
        <li><span>3</span><sup></sup></li>
    </ul>
</div>
<script>
    var prize = <?php echo $prize?>;
    var remainCount = <?php echo $remainCount?>;
    var totalCount = <?php echo $totalCount?>;
    $().ready(function () {
        $(".eggList li").hover(function () {
            var posL = $(this).position().left + $(this).width();
            $("#hammer").show().css('left', posL);
        });
        $(".eggList li").click(function () {
            $(this).children("span").hide();
            eggClick($(this));
        });
    });
    function eggClick(obj) {
        if (remainCount<=0) {
            alert("您已经砸了 " + totalCount + "次奖。明天再来吧!");
            return
        }
        if (prize ==0) {
            alert("亲，你不能再参加本次活动了喔！下次再来吧~");
            return
        }
        var _this = obj;
        $.ajax({
            url: "<?php echo Yii::app()->createUrl('egg/handle/active')?>",
            dataType: "json",
            async: false,
            data: {
                encryption: '<?php echo $encryption?>',
                t: Math.random()
            },
            success: function (data) {
                _this.unbind('click'); //解除click
                $(".hammer").css({"top": _this.position().top - 55, "left": _this.position().left + 185});
                $(".hammer").animate({//锤子动画
                        "top": _this.position().top - 25,
                        "left": _this.position().left + 125
                    }, 30, function () {
                        _this.addClass("curr"); //蛋碎效果
                        _this.find("sup").show(); //金花四溅
                        $(".hammer").hide();//隐藏锤子
                        $('.resultTip').css({
                            display: 'block', top: '100px', left: _this.position().
                                left + 45, opacity: 0
                        })
                            .animate({top: '50px', opacity: 1}, 300, function () {//中奖结果动画
                                if (data.success == 1) {//返回结果
                                    if(data.isentity==0){
                                        $("#result").html("恭喜，您中得" + data.gradeName + "!<br>礼包码为:<br>"+data.snCode);
                                    }else{
                                        $("#result").html("恭喜，您中得" + data.gradeName + "!");

                                    }
                                } else {
                                    $("#result").html("很遗憾,您没能中奖!");
                                }
                            });
                    }
                );
            },
            error: function () {
                prize = null;
            },
            timeout: 4000
        })
    }
</script>


</body>
</html>