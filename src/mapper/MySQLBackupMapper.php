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
 * @property $table
 */
class MySQLBackupMapper extends LiMapper
{
    protected $host = "";
    protected $port = "";
    protected $username = "";
    protected $password = "";
    protected $charset = "utf8mb4";
    protected $database = "";
    protected $table = [];

}