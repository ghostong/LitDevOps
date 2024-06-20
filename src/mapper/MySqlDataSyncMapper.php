<?php

namespace Lit\DevOps\mapper;


namespace Lit\DevOps\mapper;


use Lit\Utils\LiMapper;

/**
 * @property \PDO $fromPdoConn
 * @property \PDO $toPdoConn
 * @property $fromDatabase
 * @property $toDatabase
 * @property $tableName
 */
class MySqlDataSyncMapper extends LiMapper
{
    protected $fromPdoConn;
    protected $toPdoConn;
    protected $fromDatabase = '';
    protected $toDatabase = '';
    protected $tableName = '';

}