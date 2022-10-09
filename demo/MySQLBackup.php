<?php

use Lit\DevOps\mapper\MySqlBackupMapper;

include(dirname(__DIR__) . "/vendor/autoload.php");

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