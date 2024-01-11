<?php

use Lit\DevOps\Files;

include(dirname(__DIR__) . "/vendor/autoload.php");

//清理7天前的文件
Files::cleanFileByDays(["/tmp"], 7);

//清理24小时前的文件
Files::cleanFileByHours(["/tmp"], 24);

//清理60分钟前的文件
Files::cleanFileByMinutes(["/tmp"], 60);