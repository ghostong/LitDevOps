<?php

namespace Lit\DevOps\constant;


use Lit\Utils\LiConst;

/**
 * @value("CURL_EXT_NOT_EXISTS","使用此功能必须安装 php curl 扩展")
 * @value("FUNCTION_EXEC_NOT_EXISTS","备份需要 exec 函数, 当前此函数不可用")
 * @value("COMMAND_MYSQLDUMP_NOT_EXISTS","mysqldump 命令不存在")
 * @value("COMMAND_MONGODUMP_NOT_EXISTS","mongodump 命令不存在")
 * @value("DIR_NOT_WRITEABLE","目录不可写")
 * @value("DIR_NOT_EXISTS","目录不存在")
 */
class ExceptionMsg extends LiConst
{
    const CURL_EXT_NOT_EXISTS = 1001;
    const FUNCTION_EXEC_NOT_EXISTS = 1002;
    const COMMAND_MYSQLDUMP_NOT_EXISTS = 1003;
    const COMMAND_MONGODUMP_NOT_EXISTS = 1004;
    const DIR_NOT_WRITEABLE = 1005;
    const DIR_NOT_EXISTS = 1006;

}