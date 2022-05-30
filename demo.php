<?php

use Lit\DevOps\mapper\MongoDbBackupMapper;
use Lit\DevOps\mapper\MySqlBackupMapper;

include(__DIR__ . "/vendor/autoload.php");

//MySQL备份
$conf = [
    "host" => "127.0.0.1", "port" => "3306", "username" => "root", "password" => "123456",
    "charset" => "utf8mb4", "database" => "books", "mysqldump" => "/usr/bin/mysqldump"
];
$databaseConf[] = new MySqlBackupMapper($conf);
$conf = array_merge($conf, ["database" => "libraries", "tables" => ["book", "book_class", "keywords", "tag_group"]]);
$databaseConf[] = new MySqlBackupMapper($conf);

$backupResult = \Lit\DevOps\MySQL::backup($databaseConf, "/tmp/mysql");
foreach ($backupResult as $conf) {
    var_dump($conf->run_success . " " . $conf->backup_file);
}

//MongoDB备份
$conf = [
    "host" => "127.0.0.1", "port" => "27017", "username" => "root", "password" => "123456",
    "database" => "user", "mongodump" => "/usr/bin/mongodump", "auth_database" => "admin"
];
$databaseConf[] = new MongoDbBackupMapper($conf);
$backupResult = \Lit\DevOps\MongoDB::backup($databaseConf, "/tmp/mongo");
foreach ($backupResult as $conf) {
    var_dump($conf->run_success . " " . $conf->backup_dir);
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

