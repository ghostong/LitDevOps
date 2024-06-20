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
                $sql[] = LiString::array2sql($row, $config->tableName, $config->toDatabase);
                if (count($sql) > 50) {
                    $config->toPdoConn->query(implode(";", $sql));
                    $sql = [];
                }
            }
            if (!empty($sql)) {
                $config->toPdoConn->query(implode(";", $sql));
            }
            if ($config->toPdoConn->inTransaction()) {
                $config->toPdoConn->commit();
            }
        } catch (\Exception $exception) {
            $config->toPdoConn->rollBack();
        }

    }

}