<?php

namespace Lit\DevOps\source;

use Lit\DevOps\mapper\MySqlSlowLogMapper;
use Lit\DevOps\mapper\MySqlSlowLogResponseMapper;

class MySqlSlowLog
{
    /**
     * 获取MySQL慢日志
     * @date 2022/10/9
     * @param MySqlSlowLogMapper[] $databaseConfigs
     * @return MySqlSlowLogResponseMapper[][]
     * @author litong
     */
    public function run($databaseConfigs) {
        $mappers = [];
        foreach ($databaseConfigs as $databaseConf) {
            $slowLogs = $this->getSlowLog($databaseConf);
            $mappers[] = $this->similarMerge($slowLogs, $databaseConf->similarMerge);
        }
        return $mappers;
    }

    /**
     * 获取数据库中的slow_log
     * @date 2022/10/9
     * @param MySqlSlowLogMapper $databaseConf
     * @return array
     * @author litong
     */
    protected function getSlowLog($databaseConf) {
        $connect = $this->getConnect($databaseConf);
        $time = time();
        $starTime = date("Y-m-d H:i:s", $time - $databaseConf->limitTime);
        $endTime = date("Y-m-d H:i:s", $time);
        $sql = "select * from slow_log where start_time  >= '{$starTime}' and start_time < '{$endTime}' limit {$databaseConf->limitRows}";
        $query = $connect->query($sql, \PDO::FETCH_ASSOC);
        return $query->fetchAll();
    }

    /**
     * 合并相似数据
     * @param $slowLogs
     * @param $similarMerge
     * @return MySqlSlowLogResponseMapper[]
     * @date 2022/10/9
     * @author litong
     */
    protected function similarMerge($slowLogs, $similarMerge) {
        /**
         * @var MySqlSlowLogResponseMapper[] $mappers
         */
        $mappers = [];
        $maxPercent = 80;
        foreach ($slowLogs as $value) {
            if ($similarMerge) {
                $percent = 0;
                foreach ($mappers as $mapper) {
                    similar_text($mapper->sql_text, $value["sql_text"], $percent);
                    if ($percent >= $maxPercent) {
                        break;
                    }
                }
                if ($percent < $maxPercent) {
                    $mappers[] = new MySqlSlowLogResponseMapper($value);
                }
            } else {
                $mappers[] = new MySqlSlowLogResponseMapper($value);
            }
        }
        return $mappers;
    }

    /**
     * 连接数据库
     * @date 2022/10/8
     * @param MySqlSlowLogMapper $databaseConf
     * @return \PDO
     * @author litong
     */
    protected function getConnect($databaseConf) {
        return new \PDO("mysql:dbname=mysql;host=" . $databaseConf->host, $databaseConf->username, $databaseConf->password);
    }

}