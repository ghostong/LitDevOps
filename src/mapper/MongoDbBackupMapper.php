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
 * @property $mongodump
 * @property $run_success
 */
class MongoDbBackupMapper extends LiMapper
{
    //mongo主机名称
    protected $host = "";
    //mongo端口
    protected $port = "";
    //mongo用户名
    protected $username = "";
    //mongo密码
    protected $password = "";
    //mongo字符集
    protected $charset = "utf8mb4";
    //mongo备份库名
    protected $database = "";
    //mongo备份表名
    protected $tables = [];
    //mongodump 路径
    protected $mongodump = "";

    //备份目录
    protected $backup_file = "";
    //是否执行成功
    protected $run_success = false;

}