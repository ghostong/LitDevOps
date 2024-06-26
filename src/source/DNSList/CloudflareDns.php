<?php

namespace Lit\DevOps\source\DNSList;

use Lit\DevOps\mapper\DnsRecordsMapper;
use Lit\DevOps\mapper\DnsZoneMapper;
use Lit\Utils\LiHttp;

class CloudflareDns
{
    /**
     * @param $email
     * @param $bearerAuth
     * @param $authKey
     * @return DnsZoneMapper[]
     */
    public static function getList($email, $bearerAuth, $authKey) {
        $dnsZoneMappers = self::zoneList($bearerAuth);
        foreach ($dnsZoneMappers as $zoneMapper) {
            $zoneMapper->records = self::getDnsRecords($zoneMapper->zone_id, $email, $authKey);
        }
        return $dnsZoneMappers;
    }

    /**
     * @return DnsRecordsMapper[]
     */
    public static function getDnsRecords($zoneId, $email, $authKey) {
        $http = new LiHttp();
        $http->get("https://api.cloudflare.com/client/v4/zones/{$zoneId}/dns_records")
            ->setParam(['page' => 1, 'per_page' => 5000000])
            ->setHeader(["X-Auth-Email: {$email}", "X-Auth-Key: {$authKey}"])
            ->send();
        if ($http->getHttpCode() == 200) {
            $result = json_decode($http->getHttpResult(), true);
            if (empty($result) || $result['success'] != true) {
                throw new \Exception(10001, "获取 cloudflare DNS列表 失败");
            }
        } else {
            throw new \Exception(10002, "获取 cloudflare DNS列表 网络错误");
        }

        $dnsZones = [];
        foreach ($result['result'] as $record) {
            $dnsRecordsMapper = new DnsRecordsMapper();
            $dnsRecordsMapper->zone_name = $record['zone_name'];
            $dnsRecordsMapper->name = $record['name'];
            $dnsRecordsMapper->type = $record['type'];
            $dnsRecordsMapper->content = $record['content'];
            $dnsZones[] = $dnsRecordsMapper;
        }
        return $dnsZones;
    }

    /**
     * @return DnsZoneMapper[]
     */
    public static function zoneList($bearerAuth) {
        $allData = [];
        $page = 1;
        $http = new LiHttp();
        while (true) {
            $http->setHeader(["Authorization: Bearer {$bearerAuth}"])
                ->setParam(['page' => $page, 'per_page' => 50])
                ->get('https://api.cloudflare.com/client/v4/zones')->send();
            if ($http->getHttpCode() == 200) {
                $result = json_decode($http->getHttpResult(), true);
                if (empty($result) || $result['success'] != true) {
                    throw new \Exception(10001, "获取 cloudflare 域名列表 失败");
                }
                $allData = array_merge($allData, $result['result']);
                if ($result['result_info']['total_pages'] == $page) {
                    break;
                }
            } else {
                throw new \Exception(10002, "获取 cloudflare 域名列表 网络错误");
            }
        }
        $dnsZones = [];
        foreach ($allData as $zone) {
            $dnsZoneMapper = new DnsZoneMapper();
            $dnsZoneMapper->zone_id = $zone['id'];
            $dnsZoneMapper->zone_name = rtrim($zone['name'], '.');
            $dnsZones[] = $dnsZoneMapper;
        }
        return $dnsZones;
    }

}