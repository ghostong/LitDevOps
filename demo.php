<?php

use Lit\DevOps\mapper\MongoDbBackupMapper;
use Lit\DevOps\mapper\MySqlBackupMapper;

include(__DIR__ . "/vendor/autoload.php");

//MongoDB备份
//$databaseConf[] = new MongoDbBackupMapper([]);
//\Lit\DevOps\MongoDB::backup($databaseConf, "/tmp/mongo");

//MySQL备份
$conf = [
    "host" => "192.168.1.25", "port" => "3306", "username" => "root", "password" => "CA8Bq@OX5ARPifFv",
    "charset" => "utf8mb4", "database" => "copywriting", "mysqldump" => "/usr/bin/mysqldump"
];
$databaseConf[] = new MySqlBackupMapper($conf);
$conf = array_merge($conf, ["database" => "libraries", "tables" => ["xs_book", "xs_book_class", "xs_keywords", "xs_tag_group"]]);
$databaseConf[] = new MySqlBackupMapper($conf);

$backupResult = \Lit\DevOps\MySQL::backup($databaseConf, "/tmp/mysql");
foreach ($backupResult as $conf) {
    var_dump($conf->run_success . " " . $conf->backup_file);
}


//SSL 证书有效监控
$domains = ["https://baidu.com", "http://sina.com.cn"];

$data = \Lit\DevOps\SSL::checkExpire($domains);
foreach ($data as $expireMapper) {
    if ($expireMapper->success) {
        var_dump($expireMapper->domain . " 证书还有 " . $expireMapper->spare_day . " 天 " . ($expireMapper->spare_day <= 30 ? "快过期!" : "正常"));
    } else {
        var_dump($expireMapper->domain . " 证书验证失败!");
    }
}

