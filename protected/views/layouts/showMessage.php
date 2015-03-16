<?php
/* @var $this ProductController */

$this->breadcrumbs = array(
    array('name' => '首页', 'url' => array('dashboard')),
    array('name' => '提示页面'),
);
$this->pageTitle = '提示页面';
?>
<div class="box">
    <div class="box-content text-center" style='min-height: 400px;'>
        <div class="row">
            <div class="col-md-4"></div>
            <div class="col-md-4">
                <div class="alert alert-<?php echo $type == 1 ? 'info' : 'danger'; ?>">
                    <p>

                    <h3><?php echo($msg); ?></h3></p>
                    <?php if (!isset($modal)) { ?>
                        <p class="jump">
                            页面自动 <a id="href" href="<?php echo($jumpurl); ?>">跳转</a> 等待时间： <b
                                id="wait"><?php echo($wait); ?></b>
                        </p>
                    <?php } ?>
                </div>
            </div>
            <div class="col-md-4"></div>
        </div>
    </div>
</div>
<script type="text/javascript">

    (function () {
        <?php if(!isset($modal)){?>
        var wait = document.getElementById('wait'), href = document.getElementById('href').href;
        totaltime = parseInt(wait.innerHTML);
        var interval = setInterval(function () {
            var time = --totaltime;
            wait.innerHTML = "" + time;
            if (time === 0) {
                window.location.href = href;
                clearInterval(interval);
            }
            ;
        }, 1000);
        <?php }else{?>
        totaltime = 1;
        var modal = "<?php echo $modal?>";
        window.parent.$("#"+modal).find('iframe').height(300);
        var interval = setInterval(function () {
            var time = --totaltime;
            if (time === 0) {
                window.parent.$("#"+modal).modal('hide');
                clearInterval(interval);
                window.parent.$("#"+modal).find('iframe').height(700);
            }
            ;
        }, 1000);

        <?php }?>
    })();

</script>                            

