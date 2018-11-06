<?php
/**
 * Created by PhpStorm.
 * User: NickBai
 * Email: 876337011@qq.com
 * Date: 2018/11/6
 * Time: 10:11 AM
 */
function autoload($name) {

    $targetFile =  str_replace('\\', '/', $name);
    $file = realpath(__DIR__) . '/' . $targetFile . '.php';

    if(file_exists($file)) {

        require_once($file);
    }
}

spl_autoload_register('autoload');

include __DIR__ . '/vendor/autoload.php';
