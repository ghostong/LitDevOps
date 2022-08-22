<?php

use app\base\service\DingService;
use Lit\DevOps\mapper\MongoDbBackupMapper;
use Lit\DevOps\mapper\MySqlBackupMapper;
use Lit\RedisExt\MessageStore\Mapper\MessageGroupMapper;
use Lit\RedisExt\MessageStore\Mapper\SenderDingMarkdownMapper;

include(__DIR__ . "/vendor/autoload.php");

////MySQL备份
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

//http 状态码检查
$urls = ["https://www.baidu.com",
        "https://cloud.tencent.com/developer/section/1339466",
        "https://www.php.net/manual/zh/book.curl.php"];

$data = \Lit\DevOps\URL::checkStatus($urls);
foreach ($data as $urlMapper) {
        if($urlMapper->success){
            var_dump($urlMapper->url . " 状态码正常， 返回状态码是：" . $urlMapper->http_code);
        } else {
            var_dump($urlMapper->url . " 状态码异常， 返回状态码是: " . $urlMapper->http_code);
        }
        var_dump($urlMapper);
}
