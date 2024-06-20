<?php

use Lit\DevOps\Files;

include(dirname(__DIR__) . "/vendor/autoload.php");

$fromConnect = new \PDO("mysql:dbname=mysql;host=192.168.1.25", "root", "CA8Bq@OX5ARPifFv");
$toConnect = new \PDO("mysql:dbname=mysql;host=127.0.0.1", "root", "123456");

$config1 = new \Lit\DevOps\mapper\MySqlDataSyncMapper();
$config1->fromPdoConn = $fromConnect;
$config1->toPdoConn = $toConnect;
$config1->fromDatabase = 'test';
$config1->toDatabase = 'test';
$config1->tableName = 'xs_test';
$configs[] = $config1;
\Lit\DevOps\MySQL::dataSync($configs);