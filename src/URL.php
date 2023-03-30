<?php

namespace Lit\DevOps;

use Lit\DevOps\mapper\URLStatusMapper;
use Lit\DevOps\source\UrlStatus;

class URL
{
    /**
     * 检查URL的返回状态码
     * @param array $urls 要检查的URL数据
     * @return URLStatusMapper[]
     */
    public static function checkStatus($urls) {
        return (new UrlStatus())->check($urls);
    }

    /**
     * 检查URL返回的json结构
     * @param string $url 链接地址
     * @param string $conditions json验证条件
     * @return URLStatusMapper
     */
    public static function checkJson($url, $conditions) {
        return (new UrlStatus())->checkJson($url, $conditions);
    }

    /**
     * 检查URL返回是否包含字符串
     * @param string $url 链接地址
     * @param string $responseKeys 验证的字符串
     * @return URLStatusMapper
     */
    public static function checkString($url, $responseKeys) {
        return (new UrlStatus())->checkString($url, $responseKeys);
    }

}