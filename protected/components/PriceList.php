<?php
/**
 * Created by PhpStorm.
 * User: druphliu
 * Date: 14-8-16
 * Time: 下午6:38
 */

class PriceList {
    public static function returnPriceList(){
        $priceList = array(
            'vip1'=>1,
            'vip2'=>100,
            'vip3'=>200
        );
        return $priceList;
    }
} 