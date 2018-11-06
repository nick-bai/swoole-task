<?php
/**
 * Created by PhpStorm.
 * User: NickBai
 * Email: 876337011@qq.com
 * Date: 2018/11/6
 * Time: 10:11 AM
 */
namespace task;

trait TaskTrait
{
    public function onTask(\swoole\server $server, $task_id, $from_id, $data)
    {
        $pdo = new \PDO($this->config['db']['dsn'], $this->config['db']['user'], $this->config['db']['name']);

        for($i = $data['start']; $i < $data['end']; $i++) {

            try{
                $pdo->getAttribute(\PDO::ATTR_SERVER_INFO);
            } catch (\PDOException $e) {
                $pdo = new \PDO($this->config['db']['dsn'], $this->config['db']['user'], $this->config['db']['name']);
            }

            $pdo->query('INSERT INTO `pay` VALUE (null, ' . $i . ', ' . $i . ')');
        }

        return 'complete';
    }

    public function onFinish(\swoole\server $server, $task_id, $data)
    {
        $this->log->info("Task#$task_id finished, data_len=".strlen($data));
    }
}