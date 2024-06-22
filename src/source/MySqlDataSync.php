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
            while ($row = $query->fetch(\PDO::FETCH_ASSOC)) {
                $columns = implode(", ", array_keys($row));
                $values = implode(", ", array_map(function ($value) use ($config) {
                    return is_null($value) ? "NULL" : $config->toPdoConn->quote($value);
                }, array_values($row)));
                $sql = "INSERT INTO `{$config->toDatabase}`.`{$config->tableName}` ($columns) VALUES ($values)";
                $config->toPdoConn->exec($sql);
            }
            if ($config->toPdoConn->inTransaction()) {
                $config->toPdoConn->commit();
            }
        } catch (\Exception $exception) {
            $config->toPdoConn->rollBack();
            throw $exception;
        }

    }

}