<?php
/**
 * Created by PhpStorm.
 * User: NickBai
 * Email: 876337011@qq.com
 * Date: 2018/11/6
 * Time: 2:37 PM
 */
/**
 * 生成订单号
 * @return string
 */
function makeOrderId() {
    return date('YmdHis') . str_pad(mt_rand(1, 999999), 5, '0', STR_PAD_LEFT);
}

function getMsectime() {
    list($msec, $sec) = explode(' ', microtime());
    $msectime =  (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);

    return $msectime;
}

/**
 * 格式化毫秒时间
 * @return false|string
 */
function getMicrotimeFormat() {
    $time = getMsectime();
    return date('YmdHis', $time / 1000);
}

function config($key) {

    $config = include './conf/config.php';
    if(isset($config[$key])) {
        return $config[$key];
    }

    return [];
}