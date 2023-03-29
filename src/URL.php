<?php

namespace Lit\DevOps;

use Lit\DevOps\source\UrlStatus;

class URL
{
    /**
     * 检查URL的返回状态码
     * @param array $urls 要检查的URL数据
     */
    public static function checkStatus($urls) {
        return (new UrlStatus())->check($urls);
    }

    /**
     *
     * @return
     */
    public static function checkJson($url, $conditions) {
        return (new UrlStatus())->checkJson($url, $conditions);
    }

}