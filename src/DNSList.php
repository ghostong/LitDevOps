<?php

namespace Lit\DevOps;

use Lit\DevOps\source\DNSList\CloudflareDns;
use Lit\DevOps\source\DNSList\DnsPod;

class DNSList
{
    public static function awsRoute53() {

    }

    /**
     * 获取必要参数: https://dash.cloudflare.com/profile/api-tokens
     * @param $email
     * @param $bearerAuth
     * @param $authKey
     * @return mapper\DnsZoneMapper[]
     */
    public static function cloudflareDns($email, $bearerAuth, $authKey) {
        return CloudflareDns::getList($email, $bearerAuth, $authKey);
    }

    public static function aliDns() {
    }

    public static function dnsPod() {
        return DnsPod::getList();

    }

}