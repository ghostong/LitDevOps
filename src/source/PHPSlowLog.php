<?php

namespace Lit\DevOps\source;

use Lit\Utils\LiFileListen;

class PHPSlowLog
{
    public function toJson($logConf) {
        $fileList = [];
        foreach ($logConf as $file => $to) {
            $fileList[$file] = function ($line) use ($file, $to, &$lines) {
                $tmp = &$lines[$file];
                $line = trim($line);
                if (empty($line) && !empty($tmp)) {
                    $output = json_encode($tmp);
                    $tmp = [];
                    file_put_contents($to, $output . "\n", FILE_APPEND);
                } else {
                    $tmp [] = $line;
                }
            };
        }
        $fileListen = new LiFileListen($fileList);
        $fileListen->run();
    }
}