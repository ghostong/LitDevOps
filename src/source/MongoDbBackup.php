<?php


namespace Lit\DevOps\source;


use Lit\DevOps\constant\ExceptionMsg;
use Lit\DevOps\mapper\MongoDbBackupMapper;

class MongoDbBackup
{

    /**
     * @param MongoDbBackupMapper[] $databaseConf
     * @param $backupDir
     * @throws \Exception
     */
    public function __construct($databaseConf, $backupDir) {
        //依赖exec
        if (!function_exists("exec")) {
            throw new \Exception(ExceptionMsg::getComment(ExceptionMsg::FUNCTION_EXEC_NOT_EXISTS), ExceptionMsg::FUNCTION_EXEC_NOT_EXISTS);
        }

        //校验mongodump
        $commands = array_unique(array_map(function ($conf) {
            return $conf->mongodump;
        }, $databaseConf));
        foreach ($commands as $command) {
            if (!Utils::commandExists($command)) {
                throw new \Exception(ExceptionMsg::getComment(ExceptionMsg::COMMAND_MONGODUMP_NOT_EXISTS), ExceptionMsg::COMMAND_MONGODUMP_NOT_EXISTS);
            }
        }

        //验证目录可写
        if (!is_writable($backupDir)) {
            throw new \Exception(ExceptionMsg::getComment(ExceptionMsg::DIR_NOT_WRITEABLE) . " " . $backupDir, ExceptionMsg::DIR_NOT_WRITEABLE);
        }
    }

    /**
     * 执行备份命令
     * @date 2022/5/24
     * @param MongoDbBackupMapper[] $databaseConf
     * @param string $backupDir
     * @return MongoDbBackupMapper[]
     * @author litong
     */
    public function run($databaseConf, $backupDir) {
        $backupVersion = date("Ymd_His");
        foreach ($databaseConf as $key => $conf) {
            $databaseConf[$key] = $this->backupDatabase($conf, $backupDir, $backupVersion);
        }
        return $databaseConf;
    }

    /**
     * 备份数据库
     * @date 2022/5/24
     * @param MongoDbBackupMapper $conf
     * @param $backupDir
     * @param $backupVersion
     * @return MongoDbBackupMapper
     * @author litong
     */
    protected function backupDatabase($conf, $backupDir, $backupVersion) {
        $conf = $this->getBackupDir($conf, $backupDir, $backupVersion);
        //      mongodump         charset          host user pass db table > path
        $command = "%s  --default-character-set=%s -h%s -u%s -p%s %s  %s   > %s";
        $command = sprintf($command,
            $conf->mysqldump, $conf->charset, $conf->host, $conf->username, $conf->password,
            $conf->database, empty($conf->tables) ? "" : implode(" ", $conf->tables),
            $conf->backup_file
        );
        exec($command, $execRes, $resultCode);
        $conf->run_success = ($resultCode == 0);
        $conf->run_command = $command;

        return $conf;
    }

    /**
     * @date 2022/5/24
     * @param MongoDbBackupMapper $conf
     * @param $backupDir
     * @param $backupVersion
     * @return string
     * @author litong
     */
    protected function getBackupDir($conf, $backupDir, $backupVersion) {
        $conf->backup_file = $backupDir . DIRECTORY_SEPARATOR . $backupVersion . DIRECTORY_SEPARATOR .
            $conf->host . "_" . $conf->port . "_" . $conf->database . ".sql";
        !is_dir(dirname($conf->backup_file)) && mkdir(dirname($conf->backup_file), 0777, true);
        return $conf;
    }
}