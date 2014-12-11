<?php
/* @var $this SiteController */
/* @var $error array */

$this->breadcrumbs=array(
    array('name' => 'ERROR')
);
?>
<div class="page-content">
    <div class="row">
        <div class="col-xs-12">
            <!-- PAGE CONTENT BEGINS -->

            <div class="error-container">
                <div class="well">
                    <h1 class="grey lighter smaller">
											<span class="blue bigger-125">
												<i class="icon-sitemap"></i>
												Error
											</span>
                        <?php echo $code; ?>
                    </h1>

                    <hr>
                    <h3 class="lighter smaller"><?php echo CHtml::encode($message); ?></h3>

                    <div>

                        <div class="space"></div>
                        <h4 class="smaller">Try one of the following:</h4>

                        <ul class="list-unstyled spaced inline bigger-110 margin-15">
                            <li>
                                <i class="icon-hand-right blue"></i>
                                Re-check the url for typos
                            </li>

                            <li>
                                <i class="icon-hand-right blue"></i>
                                Read the faq
                            </li>

                            <li>
                                <i class="icon-hand-right blue"></i>
                                Tell us about it
                            </li>
                        </ul>
                    </div>

                    <hr>
                    <div class="space"></div>

                    <div class="center">
                        <a class="btn btn-grey" href="javascript:void(0)" onclick="window.history.go(-1)">
                            <i class="icon-arrow-left"></i>
                            Go Back
                        </a>

                        <a class="btn btn-primary" href="<?php echo Yii::app()->createUrl('wechat/index')?>">
                            <i class="icon-dashboard"></i>
                            Dashboard
                        </a>
                    </div>
                </div>
            </div><!-- PAGE CONTENT ENDS -->
        </div><!-- /.col -->
    </div><!-- /.row -->
</div>
