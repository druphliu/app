<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width,height=device-height,inital-scale=1.0,maximum-scale=1.0,user-scalable=no;">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no">
    <title>刮刮卡</title>
    <link href="<?php echo $this->module->assetsUrl; ?>/css/guaguale-activity-style.css?1" rel="stylesheet"
          type="text/css">
</head>
<script type="text/javascript">
    function loading(canvas, options) {
        this.canvas = canvas;
        if (options) {
            this.radius = options.radius || 12;
            this.circleLineWidth = options.circleLineWidth || 4;
            this.circleColor = options.circleColor || 'lightgray';
            this.moveArcColor = options.moveArcColor || 'gray';
        } else {
            this.radius = 12;
            this.circelLineWidth = 4;
            this.circleColor = 'lightgray';
            this.moveArcColor = 'gray';
        }
    }
    loading.prototype = {
        show: function () {
            var canvas = this.canvas;
            if (!canvas.getContext) return;
            if (canvas.__loading) return;
            canvas.__loading = this;
            var ctx = canvas.getContext('2d');
            var radius = this.radius;
            var me = this;
            var rotatorAngle = Math.PI * 1.5;
            var step = Math.PI / 6;
            canvas.loadingInterval = setInterval(function () {
                    ctx.clearRect(0, 0, canvas.width, canvas.height);
                    var lineWidth = me.circleLineWidth;
                    var center = {
                        x: canvas.width / 2,
                        y: canvas.height / 2
                    };

                    ctx.beginPath();
                    ctx.lineWidth = lineWidth;
                    ctx.strokeStyle = me.circleColor;
                    ctx.arc(center.x, center.y + 20, radius, 0, Math.PI * 2);
                    ctx.closePath();
                    ctx.stroke();
                    //在圆圈上面画小圆
                    ctx.beginPath();
                    ctx.strokeStyle = me.moveArcColor;
                    ctx.arc(center.x, center.y + 20, radius, rotatorAngle, rotatorAngle + Math.PI * .45);
                    ctx.stroke();
                    rotatorAngle += step;

                },
                100);
        },
        hide: function () {
            var canvas = this.canvas;
            canvas.__loading = false;
            if (canvas.loadingInterval) {
                window.clearInterval(canvas.loadingInterval);
            }
            var ctx = canvas.getContext('2d');
            if (ctx) ctx.clearRect(0, 0, canvas.width, canvas.height);
        }
    };
</script>
</head>
<body data-role="page" class="activity-scratch-card-winning">
<script src="<?php echo $this->module->assetsUrl; ?>/js/jquery.js" type="text/javascript"></script>
<script src="<?php echo $this->module->assetsUrl; ?>/js/wScratchPad.js" type="text/javascript"></script>
<div class="main">
    <div class="cover">
        <img
            src="<?php echo Yii::app()->Params['scratchPath'] . '/' . $scratch->wechatId . '/' . $scratch->backgroundPic ?>">

        <div id="prize">
            <?php if(!$disable){?><img src="<?php echo $button?>"><?php }?>
        </div>
        <div id="scratchpad">
        </div>
    </div>
    <div class="content">
        <div id="zjl" style="display:none" class="boxcontent boxwhite">
            <div class="box">
                <div class="title-red" style="color: #444444;">
							<span>
								恭喜你
							</span>
                </div>
                <div class="Detail">
                    <p>
                        你中了：
                        <span class="red" id="theAward"></span>
                    </p>

                    <?php if ($prize['grade'] == 0) { ?>
                        <p>
                            礼包码为：
								<span class="red" id="sncode">
								</span>
                        </p>
                    <?php } else { ?>
                        <p>
                            奖品为：
								<span class="red" id="sncode">
								</span>
                        </p>
                        <p class="red"></p>

                        <p>
                            <input name="" class="px" id="tel" value="" type="text" placeholder="用户请输入您的手机号">
                        </p>

                        <p>

                        <p>
                            <input class="pxbtn" name="提 交" id="save-btn" type="button" value="提交">
                        </p>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div class="boxcontent boxwhite">
            <div class="box">
                <div class="title-brown">
							<span>
								奖项设置：
							</span>
                </div>
                <div class="Detail">
                    <?php $award = unserialize($scratch->awards) ?>
                    <p>
                        一等奖： <?php echo $award[1]['name'] ?>
                    </p>

                    <p>
                        二等奖： <?php echo $award[2]['name'] ?>
                    </p>

                    <p>
                        三等奖： <?php echo $award[3]['name'] ?>
                    </p>
                </div>
            </div>
        </div>
        <?php if($totalCount!=0){?>
            <div class="boxcontent boxwhite">
                <div class="box">
                    <div class="Detail">
                        <?php if($totalCount==-1){?>
                            本次活动你只可参与<span class="red" >1</span>次，请把握机会哦!
                        <?php }else{?>
                            今天可以参与<span class="red"><?php echo $totalCount?></span>次,目前还剩
                            <span class="red"><?php echo $remainCount?></span>次
                        <?php }?>
                    </div>
                </div>
            </div>
        <?php }?>
    </div>
    <div style="clear:both;">
    </div>
</div>
<script src="<?php echo $this->module->assetsUrl; ?>/js/alert.js" type="text/javascript"></script>
<script type="text/javascript">
    window.sncode = "null";
    window.prize = "谢谢参与";
    var zjl = false;
    var num = 0;
    var goon = true;
    $(function () {
        var disable = <?php echo $disable?>;
        var msg = '<?php if($totalCount>0){?>今天刮卡次数已用完，明天再来吧<?php }else{?>本次活动你已参与了<?php }?>'
        if (!disable) {
            $("#scratchpad").mousedown(function () {
                alert(msg);
                return;
            })
        } else {
            $("#scratchpad").wScratchPad({
                width: 150,
                height: 40,
                color: "#a9a9a7",
                image2: "<?php echo $button; ?>",
                scratchMove: function () {

                    var grade = <?php echo $prize['grade']?>;
                    switch (grade) {
                        case 1:
                            var award = "一等奖";
                            var sncode = '<?php echo $prize['name']?>';
                            zjl = true;
                            break;
                        case 2:
                            var award = "二等奖";
                            var sncode = '<?php echo $prize['name']?>';
                            zjl = true;
                            break;
                        case 3:
                            var award = "三等奖";
                            var sncode = '<?php echo $prize['name']?>';
                            zjl = true;
                            break;
                        case 4:
                            var award = "四等奖";
                            var sncode = '<?php echo $prize['name']?>';
                            zjl = true;
                            break;
                        case 0:
                            var award = "游戏礼包";
                            var sncode = '<?php echo $prize['name']?>';
                            zjl = true;
                            break;
                        default :
                            var award = "谢谢参与";
                            zjl = true;
                            break;

                    }
                    document.getElementById('prize').innerHTML = award;
                    sncode ? document.getElementById('sncode').innerHTML = sncode : '';
                    $("#theAward").html(award);
                    if (zjl && goon) {
                        //$("#zjl").fadeIn();
                        goon = false;
                        var url = '<?php echo Yii::app()->createUrl("scratch/handle/confirm")?>';

                        $.ajax({
                            type: "post",
                            url: url,
                            data: 'encryption=<?php echo $encryption?>',
                            dataType: 'json',
                            success: function (data) {
                                if (data.status == true) {
                                    $("#zjl").slideToggle(500);
                                }
                            }
                        });
                        //$("#outercont").slideUp(500)

                    }
                }
            });
        }

    });

    $("#save-btn").bind("click", function () {
        var btn = $(this);
        var tel = $("#tel").val();
        if (tel.length <= 0) {
            alert("请输入手机号");
            return
        }
        var submitData = {
            code: $("#sncode").text(),
            tel: tel,
            encryption: '<?php echo $encryption?>'
        };
        var url = '<?php echo Yii::app()->createUrl("scratch/handle/save")?>';
        $.post(url, submitData,
            function (data) {
                if (data.success == true) {
                    alert(data.msg);
                    return
                } else {
                }
            },
            "json")
    });
</script>

</body>

</html>
