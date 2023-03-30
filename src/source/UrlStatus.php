<?php

namespace Lit\DevOps\source;

use Lit\DevOps\constant\ExceptionMsg;
use Lit\DevOps\constant\HttpComparison;
use Lit\DevOps\mapper\URLStatusMapper;

class UrlStatus
{
    public function __construe() {
        if (!extension_loaded("curl")) {
            throw new \Exception(ExceptionMsg::getComment(ExceptionMsg::CURL_EXT_NOT_EXISTS), ExceptionMsg::CURL_EXT_NOT_EXISTS);
        }
    }

    /**
     * 检测HTTP code
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

    public function checkString($url,$responseKeys){
        $urlStatusMapper = $this->getUrlInfo($url);
        if(!$urlStatusMapper->success){
            return $urlStatusMapper;
        }
        $responseData = $urlStatusMapper->body;
        if($responseKeys != ''){
            if(strpos($responseData, $responseKeys) !== false){
                $urlStatusMapper->success = true;
            }else {
                $urlStatusMapper->success = false;
                $urlStatusMapper->message = "返回Body验证失败";
            }
        }
        return $urlStatusMapper;
    }

    public function checkJson($url, $conditions) {
        $urlStatusMapper = $this->getUrlInfo($url);
        if (!$urlStatusMapper->success) {
            return $urlStatusMapper;
        }

        $jsonObj = json_decode($urlStatusMapper->body);
        foreach ($conditions as $condition) {
            $fields = explode(".", $condition[0]);
            $comparison = $condition[1];
            $value = $condition[2];
            $tmpObj = $this->getObjectProperties($jsonObj, $fields);
            if (is_null($tmpObj)) {
                $urlStatusMapper->success = false;
                $urlStatusMapper->message = implode(".", $fields) . " 不存在";
                break;
            }
            $result = $this->comparisonResult($tmpObj, $comparison, $value);
            if (!$result) {
                $urlStatusMapper->success = false;
                $urlStatusMapper->message = implode(".", $fields) . " 验证失败";
                break;
            }
        }
        return $urlStatusMapper;
    }


    protected function getObjectProperties($object, $fields) {
        $tmpObj = $object;
        foreach ($fields as $field) {
            if (isset($tmpObj->$field)) {
                $tmpObj = $tmpObj->$field;
            } else {
                return null;
            }
        }
        return $tmpObj;
    }

    protected function comparisonResult($obj, $comparison, $value) {
        switch ($comparison) {
            case HttpComparison::EQ;
                $result = $obj == $value;
                break;
            case HttpComparison::GT;
                $result = $obj > $value;
                break;
            case HttpComparison::LT;
                $result = $obj < $value;
                break;
            case HttpComparison::GE;
                $result = $obj >= $value;
                break;
            case HttpComparison::LE;
                $result = $obj <= $value;
                break;
            case HttpComparison::IN;
                $result = in_array($obj, $value);
                break;
            case HttpComparison::NOT_IN;
                $result = !in_array($obj, $value);
                break;
            default:
                $result = false;
        }
        return $result;
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
        // url请求重试，重试3次
        $i = $httpCode = 0;
        $responseData = "";
        while ($i < 3) {
            $responseData = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if ($httpCode != 0) {
                break;
            }
            $i++;
            sleep(1);
        }
        $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $body = substr($responseData, $headerSize);
        curl_close($ch);
        if ($httpCode == 200) {
            $success = true;
        } else {
            $success = false;
        }
        return (new URLStatusMapper(["url" => $url, "http_code" => $httpCode, "body" => $body, "success" => $success]));
    }
}