<?php

namespace Lit\DevOps\source\DNSList;

use Lit\DevOps\mapper\DnsRecordsMapper;
use Lit\DevOps\mapper\DnsZoneMapper;
use Lit\Utils\LiHttp;


class AwsRoute53
{
    /**
     * @return DnsZoneMapper[]
     */
    public static function getList($accessKeyId, $secretAccessKey, $region) {
        $dnsZoneMappers = self::getDomainList($accessKeyId, $secretAccessKey, $region);
        foreach ($dnsZoneMappers as $zoneMapper) {
            $zoneMapper->records = self::getDnsRecords($accessKeyId, $secretAccessKey, $region, $zoneMapper->zone_id, $zoneMapper->zone_name);
        }
        return $dnsZoneMappers;
    }

    /**
     * @return DnsRecordsMapper[]
     */
    public static function getDnsRecords($accessKeyId, $secretAccessKey, $region, $zoneId, $zoneName) {
        $path = "/2013-04-01{$zoneId}/rrset";
        $result = self::query($accessKeyId, $secretAccessKey, $region, $path);
        $dnsRecords = [];
        foreach ($result['ResourceRecordSets']['ResourceRecordSet'] as $recordSet) {
            $dnsRecordsMapper = new DnsRecordsMapper();
            $dnsRecordsMapper->zone_name = $zoneName;
            $dnsRecordsMapper->name = rtrim($recordSet['Name'], '.');
            $dnsRecordsMapper->type = $recordSet['Type'];
            $dnsRecordsMapper->content = implode(', ', array_column($recordSet['ResourceRecords'], 'Value'));
            $dnsRecords[] = $dnsRecordsMapper;
        }
        return $dnsRecords;
    }

    /**
     * @return DnsZoneMapper[]
     */
    public static function getDomainList($accessKeyId, $secretAccessKey, $region) {
        $path = '/2013-04-01/hostedzone';
        $result = self::query($accessKeyId, $secretAccessKey, $region, $path);
        $dnsZones = [];
        foreach ($result['HostedZones']['HostedZone'] as $hostedZone) {
            $dnsZoneMapper = new DnsZoneMapper();
            $dnsZoneMapper->zone_id = $hostedZone['Id'];
            $dnsZoneMapper->zone_name = rtrim($hostedZone['Name'], '.');
            $dnsZones[] = $dnsZoneMapper;
        }
        return $dnsZones;
    }

    /**
     * 尽可能不用SDK
     */
    protected static function query($accessKeyId, $secretAccessKey, $region, $path) {
        $baseUrl = 'https://route53.amazonaws.com';
        $headers = self::buildHeaders($accessKeyId, $secretAccessKey, 'GET', $path, '', '', $region);
        $http = new LiHttp();
        $http->setHeader($headers)->get($baseUrl . $path)->send();
        if ($http->getHttpCode() == 200) {
            return self::xmlToArray($http->getHttpResult());
        } else {
            return [];
        }
    }

    protected static function buildHeaders($accessKeyId, $secretAccessKey, $method, $path, $query, $payload, $region) {
        $service = 'route53';
        $host = 'route53.amazonaws.com';
        $algorithm = 'AWS4-HMAC-SHA256';
        $amzDate = gmdate('Ymd\THis\Z');
        $dateStamp = gmdate('Ymd');
        $canonicalUri = $path;
        $canonicalQueryString = $query;
        $canonicalHeaders = 'host:' . $host . "\n" . 'x-amz-date:' . $amzDate . "\n";
        $signedHeaders = 'host;x-amz-date';
        $payloadHash = hash('sha256', $payload);
        $canonicalRequest = $method . "\n" . $canonicalUri . "\n" . $canonicalQueryString . "\n" . $canonicalHeaders . "\n" . $signedHeaders . "\n" . $payloadHash;
        $credentialScope = $dateStamp . '/' . $region . '/' . $service . '/' . 'aws4_request';
        $stringToSign = $algorithm . "\n" . $amzDate . "\n" . $credentialScope . "\n" . hash('sha256', $canonicalRequest);
        $kSecret = 'AWS4' . $secretAccessKey;
        $kDate = hash_hmac('sha256', $dateStamp, $kSecret, true);
        $kRegion = hash_hmac('sha256', $region, $kDate, true);
        $kService = hash_hmac('sha256', $service, $kRegion, true);
        $kSigning = hash_hmac('sha256', 'aws4_request', $kService, true);
        $signature = hash_hmac('sha256', $stringToSign, $kSigning);
        $authorizationHeader = $algorithm . ' ' . 'Credential=' . $accessKeyId . '/' . $credentialScope . ', ' . 'SignedHeaders=' . $signedHeaders . ', ' . 'Signature=' . $signature;
        return array(
            'Authorization: ' . $authorizationHeader,
            'x-amz-date: ' . $amzDate,
            'Host: ' . $host,
            'Content-Type: application/x-amz-json-1.1',
        );
    }

    protected static function xmlToArray($xml) {
        $xmlString = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
        return json_decode(json_encode($xmlString), true);
    }


}