<?php

namespace App\Tasks\Registry;

use App\Core\Cli\Task\Socket;
use App\Core\Registry\Exceptions\RegistryException;
use App\Core\Registry\Input;
use swoole_server;

class ServerTask extends Socket
{
    // 端口号
    protected $port = 11521;

    protected $config = [
        'pid_file' => ROOT_PATH . '/server.pid',
        'daemonize' => false,
        'max_request' => 500, // 每个worker进程最大处理请求次数
    ];

    protected $services = [];

    protected $length = 10;

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
     *     success:true,
     *     message:"",
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
        $success = true;
        $message = '';

        try {
            if ($data = json_decode($data, true)) {
                $service = new Input($data);
                // 把元素加入到services表相应服务首位
                $key = $service->service;
                $this->services[$key] = $service->toArray();

            } else {
                throw new RegistryException("The data is invalid!");
            }
        } catch (\Exception $ex) {
            $success = false;
            $message = $ex->getMessage();
        }

        $server->send($fd, json_encode([
            'success' => $success,
            'message' => $message,
            'services' => $this->services,
        ]));
    }

}

