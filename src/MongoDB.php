<?php

namespace Lit\DevOps;

use Lit\DevOps\mapper\MongoDbBackupMapper;
use Lit\DevOps\source\MongoDbBackup;

class MongoDB
{
    /**
     * 备份MongoDB数据库
     * @date 2022/5/23
     * @param MongoDbBackupMapper[] $databaseConf
     * @param string $backupDir
     * @return MongoDbBackupMapper[]
     * @throws \Exception
     * @author litong
     */
    public static function backup($databaseConf, $backupDir) {
        return (new MongoDbBackup($databaseConf, $backupDir))->run($databaseConf, $backupDir);
    }

}