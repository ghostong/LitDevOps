<?php

use Lit\DevOps\mapper\MongoDbBackupMapper;

include(dirname(__DIR__) . "/vendor/autoload.php");

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