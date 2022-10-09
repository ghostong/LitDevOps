<?php


namespace Lit\DevOps\mapper;


use Lit\Utils\LiMapper;

/**
 * @property $host
 * @property $port
 * @property $username
 * @property $password
 * @property $charset
 * @property $limitTime
 * @property $limitRows
 * @property $similarMerge
 */
class MySqlSlowLogMapper extends LiMapper
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

    //查询多久内的日志(秒)
    protected $limitTime = 3600;
    //限制多少行
    protected $limitRows = 500;
    //查询结果是否合并相似日志
    protected $similarMerge = false;

}