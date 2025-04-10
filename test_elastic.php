<?php
require __DIR__ . '/vendor/autoload.php';

use Elasticsearch\ClientBuilder;

$client = ClientBuilder::create()->setHosts(['http://localhost:9200'])->build();

echo "Elasticsearch działa ✅\n";
