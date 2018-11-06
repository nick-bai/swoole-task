<?php
/**
 * Created by PhpStorm.
 * User: NickBai
 * Email: 876337011@qq.com
 * Date: 2018/11/6
 * Time: 10:11 AM
 */
namespace task;

trait EventTrait
{
    public function onWorkerStart(\swoole\server $server, int $worker_id)
    {
        if('Linux' == PHP_OS) {

            if($worker_id >= $server->setting['worker_num']) {

                \cli_set_process_title("task_".($worker_id - $server->setting['worker_num']));
            } else {

                \cli_set_process_title("task_{$worker_id}");
            }
        }
    }

    public function onRequest($request, $response)
    {
        if ($request->server['path_info'] == '/favicon.ico' || $request->server['request_uri'] == '/favicon.ico') {
            return $response->end(json_encode(['code' => 200, 'data' => '', 'msg' => 'SUCCESS']));
        }

        $start = $request->get['start'];
        $end = $request->get['end'];

        // TODO 防止任务重复提交

        $this->http->task(['start' => $start, 'end' => $end]);

        $response->end(json_encode(['code' => 200, 'data' => '', 'msg' => 'SUCCESS']));
    }
}