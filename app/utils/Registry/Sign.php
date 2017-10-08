<?php
// +----------------------------------------------------------------------
// | Sign.php [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2017 limingxinleo All rights reserved.
// +----------------------------------------------------------------------
// | Author: limx <715557344@qq.com> <https://github.com/limingxinleo>
// +----------------------------------------------------------------------
namespace App\Utils\Registry;

class Sign
{
    public static function sign($input = [])
    {
        ksort($input);
        $data = http_build_query($input);
        $key = env('REGISTRY_KEY');
    }
}