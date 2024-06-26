<?php

namespace Lit\DevOps\source\DNSList;


use Lit\DevOps\mapper\DnsRecordsMapper;
use Lit\DevOps\mapper\DnsZoneMapper;
use Lit\Utils\LiHttp;

function sign($key, $msg) {
    return hash_hmac("sha256", $msg, $key, true);
}

class DnsPod
{

    public static function getList($secretId, $secretKey) {
        $dnsZoneMappers = [];
        $zoneList = self::getDomainList($secretId, $secretKey);
        foreach ($zoneList as $zone) {
            $dnsRecordsMappers = [];
            $records = self::getDnsRecords($secretId, $secretKey, $zone['Name']);
            foreach ($records as $record) {
                $dnsRecordsMapper = new DnsRecordsMapper();
                $dnsRecordsMapper->zone_name = $zone['Name'];
                $dnsRecordsMapper->name = $record['Name'] . '.' . $zone['Name'];
                $dnsRecordsMapper->type = $record['Type'];
                $dnsRecordsMapper->content = $record['Value'];
                $dnsRecordsMappers[] = $dnsRecordsMapper;
            }

            $dnsZoneMapper = new DnsZoneMapper();
            $dnsZoneMapper->zone_name = $zone['Name'];
            $dnsZoneMapper->records = $dnsRecordsMappers;
            $dnsZoneMappers[] = $dnsZoneMapper;
        }
        return $dnsZoneMappers;
    }

    public static function getDnsRecords($secretId, $secretKey, $domain) {
        $action = "DescribeRecordList";
        $result = self::query($secretId, $secretKey, $action, ['Domain' => $domain, 'Offset' => 0, 'Limit' => 3000]);
        return $result['RecordList'];
    }

    public static function getDomainList($secretId, $secretKey) {
        $action = "DescribeDomainList";
        $result = self::query($secretId, $secretKey, $action, ['Offset' => 0, 'Limit' => 3000]);
        return $result['DomainList'];
    }

    protected static function query($secretId, $secretKey, $action, $params) {
        $endpoint = "https://dnspod.tencentcloudapi.com";
        $payload = empty($params) ? '{}' : json_encode($params);
        $headers = self::sign($secretId, $secretKey, $action, $payload);
        $headers = array_map(function ($v, $k) {
            return $k . ":" . $v;
        }, $headers, array_keys($headers));
        $http = new LiHttp();
        $http->setHeader($headers)->setParam($payload)->post($endpoint)->send();
        if ($http->getHttpCode() == 200) {
            $result = json_decode($http->getHttpResult(), true);
            if (!empty($result['Response'])) {
                return $result['Response'];
            } else {
                throw new \Exception(10001, "获取 DnsPod " . $action . " 失败");
            }
        } else {
            throw new \Exception(10002, "获取 DnsPod " . $action . " 网络错误");
        }
    }

    protected static function sign($secretId, $secretKey, $action, $payload) {
        $token = "";
        $reqRegion = "";
        $service = "dnspod";
        $host = "dnspod.tencentcloudapi.com";
        $version = "2021-03-23";
        $algorithm = "TC3-HMAC-SHA256";
        $timestamp = time();
        $date = gmdate("Y-m-d", $timestamp);

        // ************* 步骤 1：拼接规范请求串 *************
        $httpRequestMethod = "POST";
        $canonicalUri = "/";
        $canonicalQueryString = "";
        $ct = "application/json; charset=utf-8";
        $canonicalHeaders = "content-type:" . $ct . "\nhost:" . $host . "\nx-tc-action:" . strtolower($action) . "\n";
        $signedHeaders = "content-type;host;x-tc-action";
        $hashedRequestPayload = hash("sha256", $payload);
        $canonical_request = "$httpRequestMethod\n$canonicalUri\n$canonicalQueryString\n$canonicalHeaders\n$signedHeaders\n$hashedRequestPayload";

        // ************* 步骤 2：拼接待签名字符串 *************
        $credentialScope = "$date/$service/tc3_request";
        $hashedCanonicalRequest = hash("sha256", $canonical_request);
        $stringToSign = "$algorithm\n$timestamp\n$credentialScope\n$hashedCanonicalRequest";

        // ************* 步骤 3：计算签名 *************
        $secretDate = sign("TC3" . $secretKey, $date);
        $secretService = sign($secretDate, $service);
        $secretSigning = sign($secretService, "tc3_request");
        $signature = hash_hmac("sha256", $stringToSign, $secretSigning);

        // ************* 步骤 4：拼接 Authorization *************
        $authorization = "$algorithm Credential=$secretId/$credentialScope, SignedHeaders=$signedHeaders, Signature=$signature";

        // ************* 步骤 5：构造并发起请求 *************
        $headers = [
            "Authorization" => $authorization,
            "Content-Type" => "application/json; charset=utf-8",
            "Host" => $host,
            "X-TC-Action" => $action,
            "X-TC-Timestamp" => $timestamp,
            "X-TC-Version" => $version
        ];
        if ($reqRegion) {
            $headers["X-TC-Region"] = $reqRegion;
        }
        if ($token) {
            $headers["X-TC-Token"] = $token;
        }
        return $headers;
    }
}