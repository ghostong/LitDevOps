<?php

namespace Lit\DevOps\mapper;

use Lit\Utils\LiMapper;

/**
 * @property string $zone_id
 * @property string $zone_name
 * @property DnsRecordsMapper[] $records
 */
class DnsZoneMapper extends LiMapper
{
    protected $zone_id = "";
    protected $zone_name = "";
    protected $records = [];
}