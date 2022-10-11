<?php

include(dirname(__DIR__) . "/vendor/autoload.php");

//PHP慢日志转为Json格式

\Lit\DevOps\PHP::slowLogToJson(
    ["/data/logs/php/php-fpm.log.slow" => "/data/logs/php/php-fpm.json.log"]
);
