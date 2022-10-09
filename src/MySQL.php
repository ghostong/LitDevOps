<?php

namespace Lit\DevOps;

use Lit\DevOps\mapper\MySqlBackupMapper;
use Lit\DevOps\mapper\MySqlSlowLogMapper;
use Lit\DevOps\mapper\MySqlSlowLogResponseMapper;
use Lit\DevOps\source\MySqlBackup;
use Lit\DevOps\source\MySqlSlowLog;

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

    /**
     * 获取MySQL慢日志
     * @date 2022/10/8
     * @param MySqlSlowLogMapper[] $databaseConf
     * @return MySqlSlowLogResponseMapper[][]
     * @author litong
     */
    public static function slowLog($databaseConf) {
        return (new MySqlSlowLog())->run($databaseConf);
    }

}