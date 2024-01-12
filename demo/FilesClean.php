<?php

use Lit\DevOps\Files;

include(dirname(__DIR__) . "/vendor/autoload.php");

//清理7天前的文件 返回值为 Generator 必须遍历才会删除过期文件
foreach (Files::cleanFileByDays(["/tmp"], 7) as $realpath) {
    echo $realpath;
}

//清理24小时前的文件 返回值为 Generator 必须遍历才会删除过期文件
foreach (Files::cleanFileByHours(["/tmp"], 24) as $realpath) {
    echo $realpath;
}

//清理60分钟前的文件 返回值为 Generator 必须遍历才会删除过期文件
foreach (Files::cleanFileByMinutes(["/tmp"], 60) as $realpath) {
    echo $realpath;
}