<?php

namespace Lit\DevOps\mapper;

use Lit\Utils\LiMapper;


/**
 * @property string $zone_name
 * @property string $name
 * @property string $content
 * @property string $type
 */
class DnsRecordsMapper extends LiMapper
{

    protected $zone_name = "";

    protected $name = "";

    protected $content = "";

    protected $type = "";
}