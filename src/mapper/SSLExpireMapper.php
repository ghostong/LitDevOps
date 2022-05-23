<?php


namespace Lit\DevOps\mapper;


use Lit\Utils\LiMapper;

/**
 * @property $start_date
 * @property $expire_date
 * @property $spare_day
 * @property $domain
 * @property $http_code
 * @property $success
 */
class SSLExpireMapper extends LiMapper
{
    //域名
    protected $domain = "";

    //http状态码
    protected $http_code = "";

    //证书有效-开始时间
    protected $start_date = "";

    //证书有效-结束时间
    protected $expire_date = "";

    //证书-剩余天数
    protected $spare_day = 0;

    //成功与否
    protected $success = false;
}