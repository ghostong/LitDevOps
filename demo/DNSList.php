<?php

use Lit\DevOps\mapper\DnsRecordsMapper;

include(dirname(__DIR__) . "/vendor/autoload.php");

//
////foreach ($data as $value) {
////    foreach ($value->records as $record) {
////        if (in_array($record->type, ['CNAME', 'A'])) {
////            echo $record->name, '|', $record->content, "\n";
////        }
////    }
////}
//
//exit;

### DNSPod
$secretId = "aoeuuaoe";
$secretKey = "uaouoeu";
$data = \Lit\DevOps\DNSList::dnsPod($secretId, $secretKey);
foreach ($data as $value) {
    foreach ($value->records as $record) {
        if (in_array($record->type, ['CNAME', 'A'])) {
            echo $record->name, "\n";
        }
    }
}
exit;

### cloudflare
$email = 'a@b.com';
$bearerAuth = 'a';
$authKey = 'x';
$data = \Lit\DevOps\DNSList::cloudflareDns($email, $bearerAuth, $authKey);