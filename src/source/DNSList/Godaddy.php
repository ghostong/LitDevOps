<?php

namespace Lit\DevOps\source\DNSList;

use Lit\DevOps\mapper\DnsRecordsMapper;
use Lit\DevOps\mapper\DnsZoneMapper;
use Lit\Utils\LiHttp;

class Godaddy
{

    /**
     * @param $shopperId
     * @param $apiKey
     * @param $apiSecret
     * @return DnsZoneMapper[]
     * @throws \Exception
     */
    public static function getList($shopperId, $apiKey, $apiSecret) {
        $dnsZoneMappers = self::zoneList($shopperId, $apiKey, $apiSecret);
        sleep(1);
        foreach ($dnsZoneMappers as $k => $zoneMapper) {
            echo $k + 1, '/', count($dnsZoneMappers), "\n";
            $zoneMapper->records = self::getDnsRecords($zoneMapper->zone_name, $shopperId, $apiKey, $apiSecret);
            sleep(1);
        }
        return $dnsZoneMappers;
    }

    /**
     * @return DnsRecordsMapper[]
     * @throws \Exception
     */
    public static function getDnsRecords($domain, $shopperId, $apiKey, $apiSecret) {
        $http = new LiHttp();
        $dnsZones = [];
        $http->get("https://api.godaddy.com/v1/domains/{$domain}/records")
            ->setParam(['limit' => 500])
            ->setHeader(["accept: application/json", "X-Shopper-Id: {$shopperId}", "Authorization: sso-key {$apiKey}:{$apiSecret}"])
            ->send();
        if ($http->getHttpCode() == 404) {
            return $dnsZones;
        }
        if ($http->getHttpCode() == 200) {
            $result = json_decode($http->getHttpResult(), true);
            if (empty($result)) {
                throw new \Exception("获取 godaddy DNS列表 失败", 10001);
            }
            foreach ($result as $record) {
                $dnsRecordsMapper = new DnsRecordsMapper();
                $dnsRecordsMapper->zone_name = $domain;
                $dnsRecordsMapper->name = $record['name'] . '.' . $domain;
                $dnsRecordsMapper->type = $record['type'];
                $dnsRecordsMapper->content = $record['data'];
                $dnsZones[] = $dnsRecordsMapper;
            }
        } else {
            var_dump($http->getHttpResult());
            throw new \Exception("获取 godaddy DNS列表 网络错误", 10002);
        }
        return $dnsZones;
    }

    /**
     * @return DnsZoneMapper[]
     * @throws \Exception
     */
    public static function zoneList($shopperId, $apiKey, $apiSecret) {
        $http = new LiHttp();
        $dnsZones = [];
        $http->setHeader(["accept: application/json", "X-Shopper-Id: {$shopperId}", "Authorization: sso-key {$apiKey}:{$apiSecret}"])
            ->setParam(['limit' => 1000, 'statuses' => 'ACTIVE'])
            ->get('https://api.godaddy.com/v1/domains')->send();
        if ($http->getHttpCode() == 200) {
            $result = json_decode($http->getHttpResult(), true);
            if (empty($result)) {
                throw new \Exception("获取 godaddy 域名列表 失败", 10001);
            }
            foreach ($result as $zone) {
                $dnsZoneMapper = new DnsZoneMapper();
                $dnsZoneMapper->zone_id = $zone['domainId'];
                $dnsZoneMapper->zone_name = rtrim($zone['domain'], '.');
                $dnsZones[] = $dnsZoneMapper;
            }
        } else {
            throw new \Exception("获取 godaddy 域名列表 网络错误", 10002);
        }
        return $dnsZones;
    }

}