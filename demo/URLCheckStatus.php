<?php

use Lit\DevOps\constant\HttpComparison;

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

//接口 json返回 检测
$url = "https://www.baidu.com";
$urlMapper = \Lit\DevOps\URL::checkJson($url, [
    ["expire_time", "<", 1],
    ["pops.bookshelf.runtime", "=", false],
    ["pops.bookshelf.actions.enter.id", HttpComparison::GT, 500]
]);

var_dump($urlMapper->getInsert());

//http返回Body 关键字检查
$url = "https://www.baidu.com";
$strKeys = "baidu";

$urlMapper = \Lit\DevOps\URL::checkString($url, $strKeys);
var_dump($urlMapper->getInsert());