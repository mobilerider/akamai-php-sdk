<?php

use Akamai\Sdk\Sdk;

require __DIR__ . '/vendor/autoload.php';



Sdk::setCredentials(
    '...',
    '...',
    '...',
    '...',
    ["debug" => true]
);
$qos = Sdk::getQosService();
$id = xxxx;
$packs = $qos->getReportPack($id);

//$store = $qos->getDataStores($id);

//print_r(mr_dd($store));
//exit();

//$stores = $qos->getDataStores($id);

$format = 'Y-m-d\TH:i\Z';

$now = new DateTimeImmutable('now', new DateTimeZone('UTC'));
$minuteAgo = $now->sub(new DateInterval("PT3H"));

$dimensions = '83'; // time  Country/Area  City Title/event
$metrics = '128,130'; // errors / audience size / plays started / plays ended

try {
    $data = $qos->getData(
        $id,
        $dimensions,
        $metrics,
        $minuteAgo->format($format),
        $now->format($format)
    );
} catch (\Throwable $th) {
    var_dump($th);
}
var_dump($data);
