<?php

namespace Lit\DevOps;

use Lit\DevOps\source\DNSList\CloudflareDns;
use Lit\DevOps\source\DNSList\DnsPod;

class DNSList
{
    /**
     * 获取必要参数:
     * @return mapper\DnsZoneMapper[]
     */
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

    /**
     * 获取必要参数: https://console.cloud.tencent.com/cam/capi
     * @param $secretId
     * @param $secretKey
     * @return mapper\DnsZoneMapper[]
     */
    public static function dnsPod($secretId, $secretKey) {
        return DnsPod::getList($secretId, $secretKey);
    }

}