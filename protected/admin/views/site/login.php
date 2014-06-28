<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm  */

$this->pageTitle=Yii::app()->name . ' - Login';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title><?php echo CHtml::encode($this->pageTitle); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- basic styles -->

    <link href="<?php echo Yii::app()->request->baseUrl; ?>/assets/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/css/font-awesome.min.css" />

    <!--[if IE 7]>
    <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/css/font-awesome-ie7.min.css" />
    <![endif]-->

    <!-- page specific plugin styles -->

    <!-- fonts -->

    <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/css/fonts-goodleapis.css" />

    <!-- ace styles -->

    <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/css/ace.min.css" />
    <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/css/ace-rtl.min.css" />

    <!--[if lte IE 8]>
    <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/css/ace-ie.min.css" />
    <![endif]-->

    <!-- inline styles related to this page -->

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->

    <!--[if lt IE 9]>
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/html5shiv.js"></script>
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/respond.min.js"></script>
    <![endif]-->
</head>

<body class="login-layout">
<div class="main-container">
<div class="main-content">
<div class="row">
<div class="col-sm-10 col-sm-offset-1">
<div class="login-container">
<div class="center">
    <h1>
        <i class="icon-leaf green"></i>
        <span class="red">Ace</span>
        <span class="white">Application</span>
    </h1>
    <h4 class="blue">&copy; Company Name</h4>
</div>

<div class="space-6"></div>

<div class="position-relative">
    <div id="login-box" class="login-box visible widget-box no-border">
        <div class="widget-body">
            <div class="widget-main">
                <h4 class="header blue lighter bigger">
                    <i class="icon-coffee green"></i>
                    Please Enter Your Information
                </h4>

                <div class="space-6"></div>
                    <?php $form=$this->beginWidget('CActiveForm', array(
                        'id'=>'login-form',
                        'enableClientValidation'=>true,
                        'clientOptions'=>array(
                            'validateOnSubmit'=>true,
                            'errorCssClass'=>'errorCssClass'
                        ),
                    ));?>
                    <fieldset>
                        <label class="block clearfix">
														<span class="block input-icon input-icon-right">
                                                            <?php echo $form->textField($model,'username',array('class'=>'form-control','placeholder'=>"Username")); ?>
															<i class="icon-user"></i>
														</span>
                        </label>

                        <label class="block clearfix">
														<span class="block input-icon input-icon-right">
                                                            <?php echo $form->passwordField($model,'password',array('class'=>'form-control','placeholder'=>"Password")); ?>
															<i class="icon-lock"></i>
														</span>
                        </label>

                        <div class="space"></div>

                        <div class="clearfix">
                            <label class="inline">
                                <?php echo $form->checkBox($model,'rememberMe',array('class'=>"ace")); ?>
                                <?php echo $form->label($model,'rememberMe',array('class'=>"lbl")); ?>
                            </label>
                            <?php echo CHtml::submitButton('Login',array('class'=>"width-35 pull-right btn btn-sm btn-primary")); ?>
                        </div>
                        <div class="space-4"></div>
                    </fieldset>
                <?php $this->endWidget(); ?>
            </div><!-- /widget-main -->
        </div><!-- /widget-body -->
    </div><!-- /login-box -->
</div><!-- /position-relative -->
</div>
</div><!-- /.col -->
</div><!-- /.row -->
</div>
</div><!-- /.main-container -->

<!-- basic scripts -->

<!--[if !IE]> -->

<script src='<?php echo Yii::app()->request->baseUrl; ?>/assets/js/jquery-2.0.3.min.js'></script>

<!-- <![endif]-->

<!--[if IE]>
<script src='<?php echo Yii::app()->request->baseUrl; ?>/assets/js/jquery-1.10.2.min.js'></script>
<![endif]-->

<!--[if !IE]> -->

<script type="text/javascript">
    window.jQuery || document.write("<script src='<?php echo Yii::app()->request->baseUrl; ?>/assets/js/jquery-2.0.3.min.js'>"+"<"+"/script>");
</script>

<!-- <![endif]-->

<!--[if IE]>
<script type="text/javascript">
    window.jQuery || document.write("<script src='<?php echo Yii::app()->request->baseUrl; ?>/assets/js/jquery-1.10.2.min.js'>"+"<"+"/script>");
</script>
<![endif]-->

<script type="text/javascript">
    if("ontouchend" in document) document.write("<script src='<?php echo Yii::app()->request->baseUrl; ?>/assets/js/jquery.mobile.custom.min.js'>"+"<"+"/script>");
</script>

<!-- inline scripts related to this page -->

<script type="text/javascript">
    function show_box(id) {
        jQuery('.widget-box.visible').removeClass('visible');
        jQuery('#'+id).addClass('visible');
    }
</script>

</body>
</html>
