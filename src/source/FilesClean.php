<?php

namespace Lit\DevOps\source;

use Lit\Utils\LiFileOperator;

class FilesClean
{

    public function byDays($dirs, $expireDay) {
        return $this->byHours($dirs, $expireDay * 24);
    }

    public function byHours($dirs, $expireHour) {
        return $this->byMinutes($dirs, $expireHour * 60);
    }

    public function byMinutes($dirs, $expireMinute) {
        $cleanTime = time() - $expireMinute * 60;
        foreach ($dirs as $dir) {
            LiFileOperator::listFilesByTime($dir, LiFileOperator::CTIME, function ($fileTime, $realpath) use ($cleanTime) {
                if ($fileTime < $cleanTime) {
                    unlink($realpath);
                }
            });
        }
        return true;
    }
}