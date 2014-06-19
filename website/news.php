<?php
/**
 * Created by PhpStorm.
 * User: druphliu
 * Date: 14-6-5
 * Time: 下午10:35
 */
$key = urlencode($_GET['key']);
$contents = file_get_contents("http://www.google.com.hk/alerts/preview?q=$key&lr=zh-CN&cr=&t=7&f=1&l=0&e=");
echo $contents;
