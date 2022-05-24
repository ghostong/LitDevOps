<?php

namespace Lit\DevOps;

use Lit\DevOps\mapper\MySqlBackupMapper;
use Lit\DevOps\source\MySqlBackup;

class MySQL
{

    /**
     * 备份MySQL数据库
     * @date 2022/5/23
     * @param MySqlBackupMapper[] $databaseConf
     * @param string $backupDir
     * @return MySqlBackupMapper[]
     * @throws \Exception
     * @author litong
     */
    public static function backup($databaseConf, $backupDir) {
        return (new MySqlBackup($databaseConf, $backupDir))->run($databaseConf, $backupDir);
    }

}