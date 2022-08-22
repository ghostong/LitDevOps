<?php

namespace Lit\DevOps;

use Lit\DevOps\source\UrlStatus;

class URL
{
    /*
     * 检查URL的返回状态码
     *
     */
    public static function checkStatus($urls){
        return (new UrlStatus())->check($urls);
    }

}