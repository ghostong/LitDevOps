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
 * @property $auth_database
 * @property $backup_dir
 * @property $mongodump
 * @property $run_success
 * @property $run_command
 */
class MongoDbBackupMapper extends LiMapper
{
    //mongo主机名称
    protected $host = "";
    //mongo端口
    protected $port = "27017";
    //mongo用户名
    protected $username = "";
    //mongo密码
    protected $password = "";
    //mongo备份库名
    protected $database = "";
    //mongo验证数据库
    protected $auth_database = "admin";
    //mongodump 路径
    protected $mongodump = "";

    //备份目录
    protected $backup_dir = "";
    //是否执行成功
    protected $run_success = false;
    //备份时执行的命令
    protected $run_command = "";

}