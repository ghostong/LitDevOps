<?php

namespace Lit\DevOps\mapper;

use Lit\Utils\LiMapper;

class URLStatusMapper extends LiMapper
{
    //url
    protected $url = "";

    //http状态
    protected $http_code = "";

    //返回状态是否成功
    protected $success = false;

}