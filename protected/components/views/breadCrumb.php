
<ul id="breadCrumb" class="breadcrumb">
    <?php
    $option = null;
    foreach($this->crumbs as $k=>$crumb) {
       echo "<li>";
        if($k==0):
            echo '<i class="icon-home home-icon"></i>';
            endif;
        if(isset($crumb['url'])) {
            $option = isset($crumb['option']) ? $crumb['option'] : null;
            echo CHtml::link($crumb['name'], $crumb['url'],$option);
        } else {
            echo $crumb['name'];
        }
        echo "</li>";
    }
    ?>
</ul>