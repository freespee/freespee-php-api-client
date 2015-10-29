#!/usr/bin/env php
<?php
/**
 * Example how to use the API to fetch a source
 */

require __DIR__.'/../vendor/autoload.php';


$settings = require realpath(__DIR__.'/../settings').'/settings.php';

$cli = new \Freespee\ApiClient\Client($settings);

$res = $cli->getRequest('/sources?source_id=629200');

var_dump($res->result);
