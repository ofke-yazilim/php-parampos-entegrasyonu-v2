<?php

// Turn off error reporting
error_reporting(0);

require_once 'Config/index.php'; //Konfigirasyon tanımlamaları alınıyor
require_once 'Request/Request.php'; //Request için xml hazırlayan class ekleniyor
require_once 'Builder/Builder.php'; // İstek parametrelerini array olarak ayarlar

$request = new Request();
$builder = new Builder();

$data = [
    '-BIN-' => 454359,
];

if (isset($_GET['bin'])) {
    $data['-BIN-'] = $_GET['bin'];
}

/**
 * Aşağıda örnek bir bin numarası gönderilerek o bin numarasını bağlı olduğu kart bilgileri alınmaktadır.
 */
//Bin işlemi için gönderilecek olan xml parametreleri array olarak ayarlanıyor.
$parameters  = $builder->setBinParametres($data);

//Bin işlemi için gönderilecek olan xml request verisi hazırlanıyor
$request_xml = $request->setBinXml($parameters);

//Bin işlemi servise gönderiliyor.
$response   = $request->sendRequest($request_xml, 'bin');

if (isset($_GET['bin'])) {
    $tutar    = $_GET['tutar'];
    $pos_id   = $request->result;
    $table    = $builder->createRateTable($pos_id,$tutar);

    $response = [
                  'table' => $table,
                  'pos_id'=> $pos_id
                ];

    echo  json_encode($response);
} else{
    //Pos id değerini ekrana yazar
    echo $request->result;
}



