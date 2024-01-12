<?php

namespace Lit\DevOps\source;

use Lit\Utils\LiFileOperator;

class FilesClean
{

    /**
     * 删除X天之前的文件
     * @date 2024/1/12
     * @param $dirs
     * @param $expireDay
     * @return bool
     * @author litong
     */
    public function byDays($dirs, $expireDay) {
        return $this->byHours($dirs, $expireDay * 24);
    }

    /**
     * 删除X小时之前的文件
     * @date 2024/1/12
     * @param $dirs
     * @param $expireHour
     * @return bool
     * @author litong
     */
    public function byHours($dirs, $expireHour) {
        return $this->byMinutes($dirs, $expireHour * 60);
    }

    /**
     * 删除X分钟之前的文件
     * @date 2024/1/12
     * @param $dirs
     * @param $expireMinute
     * @return bool
     * @author litong
     */
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