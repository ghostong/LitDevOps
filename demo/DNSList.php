<?php

use Lit\DevOps\mapper\DnsRecordsMapper;

include(dirname(__DIR__) . "/vendor/autoload.php");
$data = [];

### AWS Route53
## 注意 版本限制 需要手动在包中增加  composer.json 中增加 "aws/aws-sdk-php": "^3.314"
//$accessKeyId = 'ueaoaeou';
//$secretAccessKey = 'aoeuao';
//$region = 'us-east-1';
//$data = \Lit\DevOps\DNSList::awsRoute53($accessKeyId, $secretAccessKey, $region);

### DNSPod
//$secretId = "aoueoa";
//$secretKey = "ueoaeu";
//$data = \Lit\DevOps\DNSList::dnsPod($secretId, $secretKey);

### cloudflare
//$email = 'a@b.com';
//$bearerAuth = 'a';
//$authKey = 'x';
//$data = \Lit\DevOps\DNSList::cloudflareDns($email, $bearerAuth, $authKey);

foreach ($data as $value) {
    foreach ($value->records as $record) {
        if (in_array($record->type, ['CNAME', 'A'])) {
            echo $record->zone_name, ' | ', $record->name, ' | ', $record->type, ' | ', $record->content, "\n";
//            echo $record->name, "\n";
        }
    }
}