<?php
/**
 * Created by PhpStorm.
 * User: NickBai
 * Email: 876337011@qq.com
 * Date: 2018/11/6
 * Time: 2:37 PM
 */

function config($key) {

    $config = include './conf/config.php';
    if(isset($config[$key])) {
        return $config[$key];
    }

    return [];
}