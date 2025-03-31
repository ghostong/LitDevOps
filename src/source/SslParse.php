<?php

namespace Lit\DevOps\source;

use Lit\DevOps\mapper\SSLParseMapper;

class SslParse
{

    /**
     * 解析 SSL 证书信息
     * @date 2025/3/31
     * @param $pemFile
     * @return SSLParseMapper
     * @author litong
     */
    public function parse($pemFile) {
        $cert = $this->readFile($pemFile);
        $mapper = new SSLParseMapper();
        if (!empty($cert)) {
            $x509 = @openssl_x509_read($cert);
            $parsedCert = openssl_x509_parse($x509);
            if ($parsedCert) {
                $mapper = $this->format($parsedCert, $mapper);
            }
        }
        return $mapper;
    }

    protected function format($parsedCert, $mapper) {
        $mapper->common_name = isset($parsedCert['subject']['CN']) ? $parsedCert['subject']['CN'] : '';
        $mapper->subject_alternative_name = array_map(function ($item) {
            return trim(str_replace('DNS:', '', $item));
        }, explode(',', $parsedCert['extensions']['subjectAltName']));
        $mapper->start_date = date("Y-m-d H:i:s", $parsedCert['validFrom_time_t']);
        $mapper->expire_date = date("Y-m-d H:i:s", $parsedCert['validTo_time_t']);
        $mapper->spare_day = intval(floor(($parsedCert['validTo_time_t'] - time()) / 86400));
        $mapper->success = true;
        return $mapper;
    }

    protected function readFile($pemFile) {
        if (is_file($pemFile) && is_readable($pemFile)) {
            return file_get_contents($pemFile);
        } else {
            return '';
        }
    }

}