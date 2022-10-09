<?php

include(dirname(__DIR__) . "/vendor/autoload.php");

//MySQL慢日志

//show variables like 'slow_query_log';
//show variables like 'long_query_time';
//show variables like 'log_output';
//set global log_output="table"
//set global long_query_time=2
//set global slow_query_log="ON"

$slowConf[] = new \Lit\DevOps\mapper\MySqlSlowLogMapper([
    "host" => "192.168.1.25", "port" => "3306", "username" => "root", "password" => "123456",
    "limitTime" => 3600 * 5, "limitRows" => 500, "similarMerge" => true
]);
$slowResult = \Lit\DevOps\MySQL::slowLog($slowConf);

foreach ($slowResult as $value) {
    var_dump($value);
}