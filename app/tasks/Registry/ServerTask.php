<?php

namespace App\Tasks\Registry;

use App\Core\Cli\Task\Socket;
use swoole_server;

class ServerTask extends Socket
{
    protected $config = [
        'pid_file' => ROOT_PATH . '/server.pid',
        'user' => 'nginx',
        'group' => 'nginx',
        'daemonize' => false,
        // 'worker_num' => 4, // cpu核数1-4倍比较合理 不写则为cpu核数
        'max_request' => 500, // 每个worker进程最大处理请求次数
    ];

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

    public function receive(swoole_server $server, $fd, $reactor_id, $data)
    {

    }

}

