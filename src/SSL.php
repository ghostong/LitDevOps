<?php

namespace Lit\DevOps;

use Lit\DevOps\mapper\SSLExpireMapper;
use Lit\DevOps\source\SslExpire;

class SSL
{
    /**
     * 检测SSL证书过期
     * @date 2022/5/23
     * @param array $domains 域名数组
     * @return SSLExpireMapper[]
     * @author litong
     */
    public static function checkExpire($domains) {
        return (new SslExpire())->check($domains);
    }


}