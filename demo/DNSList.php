<?php

use Lit\DevOps\mapper\DnsRecordsMapper;

include(dirname(__DIR__) . "/vendor/autoload.php");
$data = [];

### godaddy
//$shopperId = '';
//$apiKey = '';
//$apiSecret = '';
//$data = \Lit\DevOps\DNSList::Godaddy($shopperId, $apiKey, $apiSecret);

### AWS Route53
//$accessKeyId = 'ueaoaeou';
//$secretAccessKey = 'aoeuao';
//$region = 'us-east-1';
//$data = \Lit\DevOps\DNSList::awsRoute53($accessKeyId, $secretAccessKey, $region);

### DNSPod
$secretId = "";
$secretKey = "";
$onlyZoneName = "";
$data = \Lit\DevOps\DNSList::dnsPod($secretId, $secretKey, $onlyZoneName);

### cloudflare
//$email = 'a@b.com';
//$bearerAuth = 'a';
//$authKey = 'x';
//$data = \Lit\DevOps\DNSList::cloudflare($email, $bearerAuth, $authKey);

foreach ($data as $value) {
    foreach ($value->records as $record) {
        if (in_array($record->type, ['CNAME', 'A'])) {
//            echo $record->zone_name, ' | ', $record->name, ' | ', $record->type, ' | ', $record->content, "\n";
            echo $record->name, "\n";
        }
    }
}