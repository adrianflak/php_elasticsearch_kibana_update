<?php
ini_set("log_errors", 1);
//ini_set("error_log", "/var/www/cw_1/logs/app.log");
ini_set("error_log", "/var/www/php_elasticsearch_kibana/logs/app.log");

require __DIR__ . '/vendor/autoload.php';

use Elasticsearch\ClientBuilder;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$client = ClientBuilder::create()
    ->setHosts([$_ENV['ELASTIC_HOST']])
    ->build();
