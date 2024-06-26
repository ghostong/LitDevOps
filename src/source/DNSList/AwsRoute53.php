<?php

namespace Lit\DevOps\source\DNSList;

use Aws\Route53\Route53Client;
use Lit\DevOps\mapper\DnsRecordsMapper;
use Lit\DevOps\mapper\DnsZoneMapper;


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
        $route53Client = self::getClient($accessKeyId, $secretAccessKey, $region);
        $result = $route53Client->listResourceRecordSets([
            'HostedZoneId' => $zoneId,
        ]);
        $dnsRecords = [];
        foreach ($result->get('ResourceRecordSets') as $recordSet) {
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
        $route53Client = self::getClient($accessKeyId, $secretAccessKey, $region);
        $result = $route53Client->listHostedZones();
        $dnsZones = [];
        foreach ($result->get('HostedZones') as $hostedZone) {
            $dnsZoneMapper = new DnsZoneMapper();
            $dnsZoneMapper->zone_id = $hostedZone['Id'];
            $dnsZoneMapper->zone_name = rtrim($hostedZone['Name'], '.');
            $dnsZones[] = $dnsZoneMapper;
        }
        return $dnsZones;
    }

    protected static function getClient($accessKeyId, $secretAccessKey, $region) {
        $sharedConfig = [
            'credentials' => [
                'key' => $accessKeyId,
                'secret' => $secretAccessKey,
            ],
            'region' => 'us-east-1',
            'version' => 'latest'
        ];

        return new Route53Client($sharedConfig);
    }

}