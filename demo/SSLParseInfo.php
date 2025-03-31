<?php

include(dirname(__DIR__) . "/vendor/autoload.php");


$data = \Lit\DevOps\SSL::parseInfo(__DIR__ . '/nginx/public.pem');

var_dump($data->getInsert());