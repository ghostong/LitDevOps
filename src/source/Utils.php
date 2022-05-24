<?php


namespace Lit\DevOps\source;


class Utils
{
    /**
     * 命令是否存在
     * @date 2022/5/24
     * @param $command
     * @return bool
     * @author litong
     */
    public static function commandExists($command) {
        @exec($command . " --help", $execRes, $resultCode);
        if ($resultCode == 0) {
            return true;
        } else {
            return false;
        }
    }

}