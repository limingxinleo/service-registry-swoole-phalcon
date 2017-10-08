<?php
// +----------------------------------------------------------------------
// | Input.php [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2017 limingxinleo All rights reserved.
// +----------------------------------------------------------------------
// | Author: limx <715557344@qq.com> <https://github.com/limingxinleo>
// +----------------------------------------------------------------------
namespace App\Core\Registry\Exceptions;

use App\Core\Validation\Registry\InputValidator;

class Input
{
    public $service;

    public $port;

    public $ip;

    public $key;

    public function __construct($input)
    {
        $validator = new InputValidator();
        if ($validator->validate($input)->valid()) {
            throw new RegistryException($validator->getErrorMessage());
        }

        $this->service = $input['service'];
        $this->port = $input['port'];
        $this->ip = $input['ip'];


    }

    public function sign()
    {

    }
}