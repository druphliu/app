<ul class="nav nav-list">
    <?php foreach ($this->menus as $name => $menu) { ?>
        <?php if (!isset($menu['url']) && empty($menu['url'])) { ?>
            <li class="<?php if (strpos($menu['act'], $this->controller->id) !== false) { ?>active open<?php } ?>">
                <a href="#" class="dropdown-toggle">
                    <i class="<?= $menu['class'] ?>"></i>
                    <span class="menu-text"> <?= $name ?> </span>

                    <b class="arrow fa fa-angle-down"></b>
                </a>
                <ul class="submenu">
                    <?php foreach ($menu['action'] as $subMenu) { ?>
                        <?php if (isset($subMenu['url'])) { ?>
                            <li class="<?php if (strpos($subMenu['act'], $this->controller->action->id) !== false) { ?>active<?php } ?>">
                                <a href="<?php echo Yii::app()->createUrl($subMenu['url']) ?>">
                                    <i class="fa fa-angle-double-right"></i>
                                    <?= $subMenu['name'] ?>
                                </a>
                            </li>
                        <?php } else { ?>
                            <li class="<?php if (strpos($subMenu['act'], $this->controller->action->id) !== false) { ?>open<?php } ?>">
                                <a class="dropdown-toggle" href="#">
                                    <i class="fa fa-angle-double-right"></i>
                                    <?php echo $subMenu['name'] ?>
                                    <b class="arrow fa fa-angle-down"></b>
                                </a>
                                <ul class="submenu"
                                    style="<?php if (strpos($subMenu['act'], $this->controller->action->id) == false) {
                                        ?>display: none;<?php } else { ?>display: block;<?php } ?>">
                                    <?php foreach ($subMenu['action'] as $ssubMenu) { ?>
                                        <li <?php if(strpos($ssubMenu['act'], $this->controller->action->id) !=false){?>class="active"<?php }?>>
                                            <a href="<?php echo Yii::app()->createUrl($ssubMenu['url']) ?>">
                                                <i class="<?php echo $ssubMenu['class'] ?>"></i>
                                                <?php echo $ssubMenu['name'] ?>
                                            </a>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </li>
                        <?php } ?>
                    <?php } ?>
                </ul>
            </li>
        <?php } else { ?>
            <li class="<?php if (strpos($menu['url'], $this->controller->getAction()->id) !== false) { ?>active<?php } ?>">
                <a href="<?php echo Yii::app()->createUrl($menu['url']) ?>">
                    <i class="<?= $menu['class'] ?>"></i>
                    <span class="menu-text"> <?= $name ?> </span>
                </a>
            </li>
        <?php } ?>
    <?php } ?>
</ul>