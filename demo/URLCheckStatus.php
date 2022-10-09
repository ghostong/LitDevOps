<?php

include(dirname(__DIR__) . "/vendor/autoload.php");

//http 状态码检查

$urls = [
    "https://www.baidu.com",
    "https://www.php.net/manual/zh/book.curl.php"
];

$data = \Lit\DevOps\URL::checkStatus($urls);
foreach ($data as $urlMapper) {
    if ($urlMapper->success) {
        var_dump($urlMapper->url . " 状态码正常， 返回状态码是：" . $urlMapper->http_code);
    } else {
        var_dump($urlMapper->url . " 状态码异常， 返回状态码是: " . $urlMapper->http_code);
    }
}