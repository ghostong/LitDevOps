<?php

namespace Lit\DevOps;

use Lit\DevOps\source\DNSList\AwsRoute53;
use Lit\DevOps\source\DNSList\CloudflareDns;
use Lit\DevOps\source\DNSList\DnsPod;

class DNSList
{
    /**
     * 获取必要参数: https://us-east-1.console.aws.amazon.com/iam/home
     * 自定义权限
     * {
     *     "Version": "2012-10-17",
     *     "Statement": [
     *         {
     *             "Effect": "Allow",
     *             "Action": [
     *                 "route53:ListHostedZones",
     *                 "route53:ListResourceRecordSets"
     *             ],
     *             "Resource": "*"
     *         }
     *     ]
     * }
     * @return mapper\DnsZoneMapper[]
     */
    public static function awsRoute53($accessKeyId, $secretAccessKey, $region = 'us-east-1') {
        return AwsRoute53::getList($accessKeyId, $secretAccessKey, $region);
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