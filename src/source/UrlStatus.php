<?php

namespace Lit\DevOps\source;

use Lit\DevOps\constant\ExceptionMsg;
use Lit\DevOps\mapper\URLStatusMapper;

class UrlStatus
{
    public function __construe() {
        if (!extension_loaded("curl")) {
            throw new \Exception(ExceptionMsg::getComment(ExceptionMsg::CURL_EXT_NOT_EXISTS), ExceptionMsg::CURL_EXT_NOT_EXISTS);
        }
    }

    /**
     *
     * @date 2022/8/22
     * @param $urls
     * @return URLStatusMapper[]
     * @author litong
     */
    public function check($urls) {
        $urlStatus = [];
        foreach ($urls as $url) {
            $urlStatus[] = $this->getUrlInfo($url);
        }
        return $urlStatus;
    }

    /**
     * 获取url的response信息
     * http状态码是否200
     */
    protected function getUrlInfo($url) {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_TIMEOUT => 5,
            CURLOPT_HEADER => true,
            CURLOPT_USERAGENT => 'Devops/monitor',
            CURLOPT_RETURNTRANSFER => true
        ]);
        $responseData = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $body = substr($responseData, $headerSize);
        curl_close($ch);
        if ($httpCode == 200) {
            $res = new URLStatusMapper();
            $res->url = $url;
            $res->http_code = $httpCode;
            $res->success = true;
            return $res;
        } else {
            return (new URLStatusMapper(["url" => $url, "http_code" => $httpCode, "body" => $body]));
        }
    }
}