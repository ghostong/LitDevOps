<?php

namespace Lit\DevOps;

use Lit\DevOps\source\FilesClean;

class Files
{
    /**
     * 以日期为单位清理文件
     * @param array $dirs
     * @param int $expireDay
     * @return \Generator
     */
    public static function cleanFileByDays($dirs, $expireDay = 1) {
        return (new FilesClean())->byDays($dirs, $expireDay);
    }

    /**
     * 以日期为单位清理文件
     * @param array $dirs
     * @param int $expireHour
     * @return \Generator
     */
    public static function cleanFileByHours($dirs, $expireHour = 24) {
        return (new FilesClean())->byHours($dirs, $expireHour);
    }

    /**
     * 以日期为单位清理文件
     * @param array $dirs
     * @param int $expireMinute
     * @return \Generator
     */
    public static function cleanFileByMinutes($dirs, $expireMinute = 1440) {
        return (new FilesClean())->byMinutes($dirs, $expireMinute);
    }

}