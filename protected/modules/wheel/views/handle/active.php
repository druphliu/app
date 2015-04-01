<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <meta name="viewport"
          content="width=device-width,height=device-height,inital-scale=1.0,maximum-scale=1.0,user-scalable=no;">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no">
    <meta name="description" content="微信大转盘">

    <title>幸运大转盘抽奖</title>
    <link href="<?php echo $this->module->assetsUrl; ?>/css/activity-style.css" rel="stylesheet" type="text/css">
</head>

<body class="activity-lottery-winning">
<div class="main">
    <div id="outercont">
        <div id="outer-cont">
            <div id="outer" style="-webkit-transform: rotate(2136deg);"><img
                    src="<?php echo $this->module->assetsUrl; ?>/images/activity-lottery-1.png" width="310px"></div>
        </div>
        <div id="inner-cont">
            <div id="inner"><img src="<?php echo $this->module->assetsUrl; ?>/images/activity-lottery-2.png"></div>
        </div>
    </div>
    <div class="content">
        <div class="boxcontent boxyellow" id="result" style="display:none">
            <div class="box">
                <div class="title-orange"><span>恭喜你中奖了</span></div>
                <div class="Detail">

                    <p>你中了：<span class="red" id="prizetype">一等奖</span></p>
                    <p class="js_grade_name" style="display: none">奖品内容为：<span class="red" id="gradeContent"></span></p>
                    <p class="js_sn_code" style="display: none">你的兑奖SN码：<span class="red" id="sncode"></span></p>

                    <p class="red">本次兑奖码已经关联你的微信号，你可向公众号发送 兑奖 进行查询!</p>
                    <span class="js_form" style="display: none">
                        <p>
                            <input name="" class="px" id="tel" type="text" placeholder="输入您的手机号码">
                        </p>

                        <p>
                            <input class="pxbtn" id="save-btn" name="提 交" type="button" value="提 交">
                        </p>
                        </span>
                    <span class="js_btn" style="display: none">
                        <p>
                            <input class="pxbtn" id="close-btn" name="确认" type="button" value="确认">
                        </p>
                    </span>
                </div>
            </div>
        </div>
        <div class="boxcontent boxyellow">
            <div class="box">
                <div class="title-green"><span>奖项设置：</span></div>
                <div class="Detail">
                    <?php foreach($awards as $g=>$a){?>
                        <p><?php echo $grades[$g]?>等奖：<?php echo $a['name']?> </p>
                    <?php }?>
                </div>
            </div>
        </div>
    </div>

</div>
<script src="<?php echo $this->module->assetsUrl; ?>/js/jquery.js" type="text/javascript"></script>
<script type="text/javascript">
    $(function () {
        window.requestAnimFrame = (function () {
            return window.requestAnimationFrame || window.webkitRequestAnimationFrame || window.mozRequestAnimationFrame || window.oRequestAnimationFrame || window.msRequestAnimationFrame || function (callback) {
                    window.setTimeout(callback, 1000 / 60)
                }
        })();
        var grade,gradeName,snCode,gradeContent,encryption;
        var totalDeg = 360 * 3 + 0;
        var steps = [];
        var count = isentity =0;
        var now = 0;
        var a = 0.01;
        var remainCount = <?php echo $remainCount?>;
        var totalCount = <?php echo $totalCount?>;
        var isStop = <?php echo $isStop?>;
        var outter, inner, timer, running = false;

        function countSteps() {
            var t = Math.sqrt(2 * totalDeg / a);
            var v = a * t;
            for (var i = 0; i < t; i++) {
                steps.push((2 * v * i - a * i * i) / 2)
            }
            steps.push(totalDeg)
        }

        function step() {
            outter.style.webkitTransform = 'rotate(' + steps[now++] + 'deg)';
            outter.style.MozTransform = 'rotate(' + steps[now++] + 'deg)';
            if (now < steps.length) {
                requestAnimFrame(step)
            } else {
                running = false;
                setTimeout(function () {
                    if (grade >=0) {
                        $("#sncode").text(snCode);
                        $("#prizetype").text(gradeName);
                        $("#gradeContent").text(gradeContent);
                        if(isentity){
                            $(".js_grade_name").show();
                            $(".js_form").show();
                        }else{
                            $(".js_sn_code").show();
                            $(".js_btn").show();
                        }
                        $("#result").slideToggle(500);
                        $("#outercont").slideUp(500)
                    } else {
                        alert("谢谢您的参与，下次再接再厉")
                    }
                }, 200)
            }
        }

        function start(deg) {
            running = true;
            clearInterval(timer);
            totalDeg = 360 * 5 + deg;
            steps = [];
            now = 0;
            countSteps();
            requestAnimFrame(step)
        }
        window.start = start;
        outter = document.getElementById('outer');
        inner = document.getElementById('inner');
        i = 10;
        $("#inner").click(function () {
            if (running)return;
            if (remainCount<=0) {
                alert("您已经抽了 "+totalCount+" 次奖。明天再来吧！");
                return
            }
            if (isStop==1) {
                alert("亲，你不能再参加本次活动了喔！下次再来吧~");
                return
            }
            $.ajax({
                url: "<?php echo $this->createUrl('handle/active')?>",
                dataType: "json",
                data: {encryption: "<?php echo $encryption?>", t: Math.random()},
                beforeSend: function () {
                    running = true;
                    timer = setInterval(function () {
                        i += 5;
                        outter.style.webkitTransform = 'rotate(' + i + 'deg)';
                        outter.style.MozTransform = 'rotate(' + i + 'deg)'
                    }, 1)
                },
                success: function (data) {
                    remainCount = data.remainCount;
                    totalCount = data.totalCount;
                    if (data.error == "invalid") {
                        alert("您已经抽了 " + totalCount + " 次奖。");
                        clearInterval(timer);
                        return
                    }
                    if (data.success) {
                        prize = data.prize;
                        grade = data.grade;
                        encryption = data.encryption;
                        isentity = data.isentity;
                        if(grade>=0){
                            gradeName = data.gradeName;
                            snCode = data.snCode;
                            gradeContent = data.gradeContent;
                        }
                        start(prize);
                    } else {
                        start(0)
                    }
                    running = false;
                    count++
                },
                error: function () {
                    start(0);
                    running = false;
                    count++
                },
                timeout: 4000
            })
        })
        $("#save-btn").bind("click", function () {
            var btn = $(this);
            var tel = $("#tel").val();
            if (tel == '') {
                alert("请输入手机号码");
                return
            }
            var regu = /^[1][0-9]{10}$/;
            var re = new RegExp(regu);
            if (!re.test(tel)) {
                alert("请输入正确手机号码");
                return
            }

            var submitData = {tel: tel, encryption: encryption};
            var url = '<?php echo $this->createUrl("save")?>';
            $.post(url, submitData, function (data) {
                if (data.success == true) {
                    alert("提交成功，谢谢您的参与");
                    window.location.href='';
                    return
                } else {
                }
            }, "json")
        });
    });

    $("#close-btn").click(function(){
        $("#result").hide();
        $("#outercont").show();
        window.location.href='';
    })
</script>


</body>
</html>
