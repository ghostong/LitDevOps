<?php

include(dirname(__DIR__) . "/vendor/autoload.php");

//SSL 证书有效监控

$domains = ["https://baidu.com", "https://sina.com.cn"];

$data = \Lit\DevOps\SSL::checkExpire($domains);
foreach ($data as $expireMapper) {
    if ($expireMapper->success) {
        var_dump($expireMapper->domain . " 证书还有 " . $expireMapper->spare_day . " 天 " . ($expireMapper->spare_day <= 30 ? "快过期!" : "正常"));
    } else {
        var_dump($expireMapper->domain . " 证书验证失败!");
    }
}