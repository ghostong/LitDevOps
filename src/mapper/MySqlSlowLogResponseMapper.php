<?php


namespace Lit\DevOps\mapper;


use Lit\Utils\LiMapper;

/**
 * @property $start_time
 * @property $user_host
 * @property $query_time
 * @property $lock_time
 * @property $rows_sent
 * @property $rows_examined
 * @property $db
 * @property $last_insert_id
 * @property $insert_id
 * @property $server_id
 * @property $sql_text
 * @property $thread_id
 * @property $occ_num 出现次数
 */
class MySqlSlowLogResponseMapper extends LiMapper
{
    protected $start_time = "";
    protected $user_host = "";
    protected $query_time = "";
    protected $lock_time = "";
    protected $rows_sent = "";
    protected $rows_examined = "";
    protected $db = "";
    protected $last_insert_id = "";
    protected $insert_id = "";
    protected $server_id = "";
    protected $sql_text = "";
    protected $thread_id = "";
    protected $occ_num = 1;
}