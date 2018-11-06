# swoole-task

#### 项目介绍
swoole-task框架   
方便您执行异步任务

#### 安装教程

1. 拥有php环境且版本高于 7.0
2. 安装swoole扩展，建议 4.x


#### 使用说明

进入项目目录，运行  
```
[启动]      php bin/task start
[启动守护]   php bin/task start -d
[重启]      php bin/task restart
[重启守护]   php bin/task restart -d
[结束运行]   php bin/task stop
```

#### 如何使用 

EventTrait.php 的 onRequest 方法，通过http 接受任务投递。 
TaskTrait.php 的 onTask 方法，用来消耗 任务
