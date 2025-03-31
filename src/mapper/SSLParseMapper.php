<?php

namespace Lit\DevOps\mapper;

use Lit\Utils\LiMapper;

/**
 * @property $start_date
 * @property $expire_date
 * @property $spare_day
 * @property $common_name
 * @property $subject_alternative_name
 * @property $success
 */
class SSLParseMapper extends LiMapper
{
    //CN (Common Name) : 证书所保护的域名
    protected $common_name = "";

    //SAN (Subject Alternative Name) : 备用主体名称
    protected $subject_alternative_name = [];

    //证书有效-开始时间
    protected $start_date = "";

    //证书有效-结束时间
    protected $expire_date = "";

    //证书-剩余天数
    protected $spare_day = 0;

    //成功与否
    protected $success = false;
}