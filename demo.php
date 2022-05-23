<?php

use Lit\DevOps\mapper\MySQLBackupMapper;

include(__DIR__ . "/vendor/autoload.php");

//MySQL备份
$backupMapper = new MySQLBackupMapper();
$backupMapper->host = "127.0.0.1";
$backupMapper->port = "23306";
$backupMapper->username = "root";
$backupMapper->password = "123456";
$backupMapper->charset = "utf8mb4";
$backupMapper->database = "ibeautys";

$conf[] = $backupMapper;
\Lit\DevOps\MySQL::backup($conf, "/tmp");


//SSL 证书有效监控
//$domains = ["https://baidu.com", "http://sina.com.cn"];
//
//$data = \Lit\DevOps\SSL::checkExpire($domains);
//foreach ($data as $expireMapper) {
//    if ($expireMapper->success) {
//        var_dump($expireMapper->domain . " 证书还有 " . $expireMapper->spare_day . " 天 " . ($expireMapper->spare_day <= 30 ? "快过期!" : "正常"));
//    } else {
//        var_dump($expireMapper->domain . " 证书验证失败!");
//    }
//}

