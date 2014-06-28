
<ul id="breadCrumb" class="breadcrumb">
    <?php
    foreach($this->crumbs as $k=>$crumb) {
       echo "<li>";
        if($k==0):
            echo '<i class="icon-home home-icon"></i>';
            endif;
        if(isset($crumb['url'])) {
            echo CHtml::link($crumb['name'], $crumb['url']);
        } else {
            echo $crumb['name'];
        }
        echo "</li>";
    }
    ?>
</ul>