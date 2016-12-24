<?php  
require 'yandex_geo/vendor/autoload.php';

if (isset($_GET['address'])) {
    $api = new \Yandex\Geo\Api();
    $api
        ->setQuery($_GET['address'])
        ->load();
    $response = $api->getResponse();
    $result = $response->getList();
}

include 'index.html';
?>