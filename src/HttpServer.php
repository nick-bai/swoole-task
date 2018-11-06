<?php
/**
 * Created by PhpStorm.
 * User: NickBai
 * Email: 876337011@qq.com
 * Date: 2018/11/6
 * Time: 10:11 AM
 */
namespace task;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class HttpServer
{
    use EventTrait, TaskTrait;

    public $http = null;
    private $config;
    private $log = null;

    public function __construct(array $config)
    {
        $this->config = $config;

        $this->log = new Logger('task_log');
        $this->log->pushHandler(new StreamHandler($this->config['log_file']));
    }

    public function run()
    {
        $this->checkEnv();
        $this->parseCommand() &&  $this->start();
    }

    private function start(): void
    {
        $this->http = new \swoole\http\server("127.0.0.1", $this->config['port']);
        $this->http->set($this->config['swoole']);

        $this->http->on('WorkerStart', [$this, 'onWorkerStart']);
        $this->http->on('Request', [$this, 'onRequest']);
        $this->http->on('Task', [$this, 'onTask']);
        $this->http->on('Finish', [$this, 'onFinish']);

        $this->http->start();
    }

    private function checkEnv(): void
    {
        if (php_sapi_name() != "cli") {
            exit("only run in command line mode " . PHP_EOL);
        }
    }

    private function parseCommand(): bool
    {
        global $argv;

        $availableCommands = [
            'start',
            'stop',
            'restart',
            'reload'
        ];

        if (!isset($argv[1]) || !in_array($argv[1], $availableCommands)) {
            if (isset($argv[1])) {

                $msg = 'Unknown command: ' . $argv[1];
                $this->log->error($msg);
            }

            $msg =<<<EOL
    php start.php {command} [-d]
        
    Available Commands:
      
        start        start the server
        start -d     run as a daemon
        stop         stop the server
        restart      restart the server
        restart -d   restart the server and run as a daemon
        
EOL;
            $msg .= PHP_EOL;
            exit($msg);
        }

        $command  = trim($argv[1]);
        $command2 = isset($argv[2]) ? $argv[2] : '';

        switch ($command) {
            case 'start':

                if($this->isRunning()) {

                    $msg = 'server is running, please run stop or restart if you want to restart server';
                    $this->log->error($msg);

                    return false;
                }

                if ($command2 === '-d') {

                    $this->config['swoole']['daemonize'] = 1;
                }

                $msg = 'server run ';
                $this->log->info($msg);

                return true;
                break;
            case 'stop':

                $this->stop();
                exit(0);

                break;
            case 'restart':

                if ($command2 === '-d') {

                    if($this->restart()) {
                        return false;
                    }
                }else {

                    $this->config['swoole']['daemonize'] = 0;
                    $this->stop();
                    return true;
                }

                break;
        }

        return true;
    }

    private function stop(): bool
    {
        $pidFile = $this->config['swoole']['pid_file'];

        if (file_exists($pidFile)) {
            $pid = file_get_contents($pidFile);

            $sig = SIGTERM;
            if (!\swoole\process::kill($pid, 0)) {

                $msg = "PID :{$pid} not exist";
                $this->log->error($msg);

                return false;
            }

            \swoole\process::kill($pid, $sig);

            // 等待5秒
            $time = time();
            $flag = false;
            while (true) {
                usleep(1000);
                if (!\swoole\process::kill($pid, 0)) {

                    $msg = "server stop at " . date("Y-m-d H:i:s");
                    $this->log->error($msg);

                    if (is_file($pidFile)) {
                        unlink($pidFile);
                    }
                    $flag = true;
                    break;
                } else {

                    if (time() - $time > 5) {

                        $msg = 'stop server fail.try again ';
                        $this->log->warning($msg);

                        break;
                    }
                }
            }
            return $flag;

        } else {

            $msg = 'pid 文件不存在，请执行查找主进程pid,kill!';
            $this->log->error($msg);

            return false;
        }
    }

    private function restart(): bool
    {
        $pidFile = $this->config['swoole']['pid_file'];

        if (file_exists($pidFile)) {
            $sig = SIGUSR1;
            $pid = file_get_contents($pidFile);
            if (!\swoole\process::kill($pid, 0)) {

                $msg = "pid :{$pid} not exist";
                $this->log->error($msg);

                return false;
            }

            \swoole\process::kill($pid, $sig);

            $msg = "send server reload command at " . date("Y-m-d H:i:s");
            $this->log->info($msg);

            return true;
        } else {

            $msg = 'pid 文件不存在，请执行查找主进程pid,kill!';
            $this->log->error($msg);

            return false;
        }
    }

    private function isRunning(): bool
    {
        $pidFile = $this->config['swoole']['pid_file'];

        if (file_exists($pidFile)) {
            $pid = file_get_contents($pidFile);
            if (\swoole\process::kill($pid, 0)) {

                return true;
            }

            return false;
        }

        return false;
    }
}