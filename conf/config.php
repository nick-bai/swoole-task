<?php
/**
 * Created by PhpStorm.
 * User: NickBai
 * Email: 876337011@qq.com
 * Date: 2018/11/6
 * Time: 10:19 AM
 */

return [
    'db' => [
        'dsn' => 'mysql:host=127.0.0.1;dbname=test;charset=utf8',
        'user' => 'root',
        'name' => 'root'
    ],

    'swoole' => [
        'worker_num' => 4,
        'task_worker_num' => 32,
        'pid_file' => __DIR__ . '/../task.pid',
        'daemonize' => 0
    ],

    'port' => 9501,

    'log_file' => __DIR__ . '/../logs/' . date('Y-m-d') . '.log',

    // 渠道简码与渠道类对应
    'pay_class_code' => [
        'OF' => 'OfPay',
        'SJSB' => 'NineteenPay'
    ],

    // 异步回调域名
    'notify_url' => 'https://recharge.hbfintech.com',
];