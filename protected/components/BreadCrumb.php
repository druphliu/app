<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-6-28
 * Time: 下午2:15
 */
class BreadCrumb extends CWidget {
    public $crumbs = array();

    public function run() {
        $this->render('breadCrumb');
    }

}