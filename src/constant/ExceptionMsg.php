<?php

namespace Lit\DevOps\constant;


use Lit\Utils\LiConst;

/**
 * @value("CURL_EXT_NOT_EXISTS","使用此功能必须安装 php curl 扩展")
 * @value("PDO_EXT_NOT_EXISTS","使用此功能必须安装 php pdo_mysql 扩展")
 */
class ExceptionMsg extends LiConst
{
    const CURL_EXT_NOT_EXISTS = 1001;
    const PDO_EXT_NOT_EXISTS = 1001;

}