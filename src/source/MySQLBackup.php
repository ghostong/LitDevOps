<?php


namespace Lit\DevOps\source;


use Lit\DevOps\constant\ExceptionMsg;

class MySQLBackup
{
    public function __construct() {
        if (!extension_loaded("pdo_mysql")) {
            throw new \Exception(ExceptionMsg::getComment(ExceptionMsg::CURL_EXT_NOT_EXISTS), ExceptionMsg::CURL_EXT_NOT_EXISTS);
        }
    }

    public function run($databaseConf, $saveDir) {
//        var_dump($databaseConf, $saveDir);
        foreach ($databaseConf as $conf) {

        }
    }

}