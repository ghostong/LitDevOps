<?php

namespace Lit\DevOps\source;

use Lit\DevOps\mapper\MySqlDataSyncMapper;
use Lit\Utils\LiString;

class MySqlDataSync
{

    public function run($sysConfigs) {
        foreach ($sysConfigs as $config) {
            $this->copyData($config);
        }
    }
    
    protected function copyData(MySqlDataSyncMapper $config) {
        $query = $config->fromPdoConn->query("select * from `{$config->toDatabase}`.`{$config->tableName}`");
        $config->toPdoConn->beginTransaction();
        try {
            $config->toPdoConn->query("delete from `$config->toDatabase`.`$config->tableName`");
            $sql = [];
            while ($row = $query->fetch(\PDO::FETCH_ASSOC)) {
                $sql[] = $this->makeSql($row, $config);
                if (count($sql) >= 50) {
                    $config->toPdoConn->exec(implode(";", $sql));
                }
            }
            if (count($sql) > 0) {
                $config->toPdoConn->exec(implode(";", $sql));
            }
            if ($config->toPdoConn->inTransaction()) {
                $config->toPdoConn->commit();
            }
        } catch (\Exception $exception) {
            $config->toPdoConn->rollBack();
            throw $exception;
        }
    }

    protected function makeSql($row, $config) {
        $columns = implode(", ", array_keys($row));
        $values = implode(", ", array_map(function ($value) use ($config) {
            return is_null($value) ? "NULL" : $config->fromPdoConn->quote($value);
        }, array_values($row)));
        return "insert into `{$config->toDatabase}`.`{$config->tableName}` ($columns) values ($values)";
    }

}