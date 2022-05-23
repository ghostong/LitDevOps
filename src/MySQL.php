<?php

namespace Lit\DevOps;

use Lit\DevOps\mapper\MySQLBackupMapper;
use Lit\DevOps\source\MySQLBackup;

class MySQL
{

    /**
     *
     * @date 2022/5/23
     * @param MySQLBackupMapper[] $databaseConf
     * @param $saveDir
     * @return void
     * @author litong
     */
    public static function backup($databaseConf, $saveDir) {
        (new MySQLBackup())->run($databaseConf, $saveDir);
    }

}