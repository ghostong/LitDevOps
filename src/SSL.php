<?php

namespace Lit\DevOps;

use Lit\DevOps\mapper\SSLExpireMapper;
use Lit\DevOps\mapper\SSLParseMapper;
use Lit\DevOps\source\SslExpire;
use Lit\DevOps\source\SslParse;

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

    /**
     * 获取证书信息
     * @date 2025/3/31
     * @param $pemFile
     * @return SSLParseMapper
     * @author litong
     */
    public static function parseInfo($pemFile) {
        return (new SslParse())->parse($pemFile);
    }

}