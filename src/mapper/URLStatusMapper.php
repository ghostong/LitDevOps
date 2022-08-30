<?php

namespace Lit\DevOps\mapper;

use Lit\Utils\LiMapper;

/**
 * @property $url
 * @property $http_code
 * @property $success
 */
class URLStatusMapper extends LiMapper
{
    //url
    protected $url = "";

    //http状态
    protected $http_code = "";

    //返回状态是否成功
    protected $success = false;

    //Response body
    protected $body = "";

}