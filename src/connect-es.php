<?php
    require("vendor/autoload.php");

    // use Elasticsearch\Client;
    use Elasticsearch\ClientBuilder;

    $host = [
        [
            "host" => "127.0.0.1",
            "port" => "9200",
            "schema" => "http",
        ]
    ];

    $client = ClientBuilder::create()->setHosts($host)->build();
