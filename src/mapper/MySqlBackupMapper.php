<?php


namespace Lit\DevOps\mapper;


use Lit\Utils\LiMapper;

/**
 * @property $host
 * @property $port
 * @property $username
 * @property $password
 * @property $charset
 * @property $database
 * @property $tables
 * @property $backup_file
 * @property $mysqldump
 * @property $run_success
 * @property $run_command
 */
class MySqlBackupMapper extends LiMapper
{
    //mysql主机名称
    protected $host = "";
    //mysql端口
    protected $port = "3306";
    //mysql用户名
    protected $username = "";
    //mysql密码
    protected $password = "";
    //mysql字符集
    protected $charset = "utf8mb4";
    //mysql备份库名
    protected $database = "";
    //mysql备份表名
    protected $tables = [];
    //mysqldump 路径
    protected $mysqldump = "";

    //备份文件
    protected $backup_file = "";
    //是否执行成功
    protected $run_success = false;
    //备份时执行的命令
    protected $run_command = "";

}