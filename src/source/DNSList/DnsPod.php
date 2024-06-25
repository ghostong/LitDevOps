<?php

namespace Lit\DevOps\source\DNSList;


function sign($key, $msg) {
    return hash_hmac("sha256", $msg, $key, true);
}

class DnsPod
{

    public static function getList() {


        $secret_id = "";
        $secret_key = "";
        $token = "";

        $service = "dnspod";
        $host = "dnspod.tencentcloudapi.com";
        $req_region = "";
        $version = "2021-03-23";
        $action = "DescribeDomainList";
        $payload = "{}";
        $params = json_decode($payload);
        $endpoint = "https://dnspod.tencentcloudapi.com";
        $algorithm = "TC3-HMAC-SHA256";
        $timestamp = time();
        $date = gmdate("Y-m-d", $timestamp);

// ************* 步骤 1：拼接规范请求串 *************
        $http_request_method = "POST";
        $canonical_uri = "/";
        $canonical_querystring = "";
        $ct = "application/json; charset=utf-8";
        $canonical_headers = "content-type:" . $ct . "\nhost:" . $host . "\nx-tc-action:" . strtolower($action) . "\n";
        $signed_headers = "content-type;host;x-tc-action";
        $hashed_request_payload = hash("sha256", $payload);
        $canonical_request = "$http_request_method\n$canonical_uri\n$canonical_querystring\n$canonical_headers\n$signed_headers\n$hashed_request_payload";

// ************* 步骤 2：拼接待签名字符串 *************
        $credential_scope = "$date/$service/tc3_request";
        $hashed_canonical_request = hash("sha256", $canonical_request);
        $string_to_sign = "$algorithm\n$timestamp\n$credential_scope\n$hashed_canonical_request";

// ************* 步骤 3：计算签名 *************
        $secret_date = sign("TC3" . $secret_key, $date);
        $secret_service = sign($secret_date, $service);
        $secret_signing = sign($secret_service, "tc3_request");
        $signature = hash_hmac("sha256", $string_to_sign, $secret_signing);

// ************* 步骤 4：拼接 Authorization *************
        $authorization = "$algorithm Credential=$secret_id/$credential_scope, SignedHeaders=$signed_headers, Signature=$signature";

// ************* 步骤 5：构造并发起请求 *************
        $headers = [
            "Authorization" => $authorization,
            "Content-Type" => "application/json; charset=utf-8",
            "Host" => $host,
            "X-TC-Action" => $action,
            "X-TC-Timestamp" => $timestamp,
            "X-TC-Version" => $version
        ];
        if ($req_region) {
            $headers["X-TC-Region"] = $req_region;
        }
        if ($token) {
            $headers["X-TC-Token"] = $token;
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $endpoint);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array_map(function ($k, $v) {
            return "$k: $v";
        }, array_keys($headers), $headers));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        echo $response;


    }

    public static function getDomainList() {

    }
}