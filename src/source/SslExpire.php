<?php

namespace Lit\DevOps\source;


use Lit\DevOps\constant\ExceptionMsg;
use Lit\DevOps\mapper\SSLExpireMapper;
use Lit\Utils\LiString;

class SslExpire
{
    public function __construct() {
        if (!extension_loaded("curl")) {
            throw new \Exception(ExceptionMsg::getComment(ExceptionMsg::CURL_EXT_NOT_EXISTS), ExceptionMsg::CURL_EXT_NOT_EXISTS);
        }
    }

    /**
     * 检测SSL证书过期
     * @date 2022/5/23
     * @param array $domains 域名数组
     * @return SSLExpireMapper[]
     * @author litong
     */
    public function check($domains) {
        $domains = array_map(function ($domain) {
            return $this->formatDomain($domain);
        }, $domains);
        $domains = array_unique($domains);
        $checkExpire = [];
        foreach ($domains as $domain) {
            $checkExpire[] = $this->getSslInfo($domain);
        }
        return $checkExpire;
    }


    /**
     * 获取SSL 信息
     * @date 2022/5/23
     * @param string $domain 域名
     * @return SSLExpireMapper
     * @author litong
     */
    protected function getSslInfo($domain) {
        $tmpFile = tmpfile();
        $ch = curl_init($domain);
        curl_setopt_array($ch, [
            CURLOPT_HEADER => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_STDERR => $tmpFile,
            CURLOPT_VERBOSE => true,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_CONNECTTIMEOUT => 30,
            CURLOPT_TIMEOUT => 60
        ]);
        curl_exec($ch);
        $sslVerify = curl_getinfo($ch, CURLINFO_SSL_VERIFYRESULT);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        fseek($tmpFile, 0);
        $read = fread($tmpFile, 10240);
        fclose($tmpFile);
        if ($sslVerify === 0 && $httpCode > 0) {
            $sem = $this->explainTime($read);
            $sem->domain = $domain;
            $sem->http_code = $httpCode;
            $sem->success = true;
            return $sem;
        } else {
            return (new SSLExpireMapper(["domain" => $domain, "http_code" => $httpCode]));
        }
    }

    /**
     * 检测地址格式化
     * @date 2022/5/23
     * @param string $domain 域名
     * @return string
     * @author litong
     */
    protected function formatDomain($domain) {
        $domain = "https://" . str_ireplace(["http://", "https://"], "", $domain);
        $host = parse_url($domain, PHP_URL_HOST);
        $port = parse_url($domain, PHP_URL_PORT);
        return "https://" . $host . ((null == $port) ? "" : (":" . $port));
    }

    /**
     * 提取过期时间
     * @date 2022/5/23
     * @param string $curlRes curl结果
     * @return SSLExpireMapper
     * @author litong
     */
    protected function explainTime($curlRes) {
        $curlRes = strtolower($curlRes);
        $sem = new SSLExpireMapper();

        $startTimestamp = strtotime(LiString::subStr($curlRes, "start date:", "\n"));
        $sem->start_date = $startTimestamp > 0 ? date("Y-m-d H:i:s", $startTimestamp) : "";

        $endTimestamp = strtotime(LiString::subStr($curlRes, "expire date:", "\n"));
        $sem->expire_date = $endTimestamp > 0 ? date("Y-m-d H:i:s", $endTimestamp) : "";

        $sem->spare_day = $endTimestamp > 0 ? intval(floor(($endTimestamp - time()) / 86400)) : 0;

        return $sem;
    }
}