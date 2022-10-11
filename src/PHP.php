<?php

namespace Lit\DevOps;


use Lit\DevOps\source\PHPSlowLog;

class PHP
{
    /**
     * php slow log 转为json
     * @date 2022/10/11
     * @param $logConf
     * @return void
     * @author litong
     */
    public static function slowLogToJson($logConf) {
        (new PHPSlowLog())->toJson($logConf);
    }

}