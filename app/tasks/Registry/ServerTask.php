<?php

namespace App\Tasks\Registry;

use App\Core\Cli\Task\Socket;
use swoole_server;

class ServerTask extends Socket
{
    // 端口号
    protected $port = 11521;

    protected $config = [
        'pid_file' => ROOT_PATH . '/server.pid',
        'user' => 'nginx',
        'group' => 'nginx',
        'daemonize' => false,
        // 'worker_num' => 4, // cpu核数1-4倍比较合理 不写则为cpu核数
        'max_request' => 500, // 每个worker进程最大处理请求次数
    ];

    protected $services = [];

    protected function events()
    {
        return [
            'receive' => [$this, 'receive'],
            'WorkerStart' => [$this, 'workerStart'],
        ];
    }

    public function workerStart(swoole_server $serv, $workerId)
    {
        // dump(get_included_files()); // 查看不能被平滑重启的文件
    }

    /**
     * @desc
     * @author limx
     * @param swoole_server $server
     * @param int           $fd
     * @param int           $reactor_id
     * @param string        $data
     *
     * input = {
     *     service:xxx,
     *     ip:xxx,
     *     port:xxx,
     *     nonce:xxx,
     *     sign:xxxx,
     * }
     *
     * output = {
     *     services:[{
     *         service:xxx,
     *         ip:xxx,
     *         port:xxx,
     *         weight:xxx
     *     },...],
     * }
     */
    public function receive(swoole_server $server, $fd, $reactor_id, $data)
    {
        if ($data = json_decode($data, true)) {
            $result = [];
            $server->send($fd, json_encode($data));
        } else {
            $server->send($fd, json_encode([]));
        }
    }

}

