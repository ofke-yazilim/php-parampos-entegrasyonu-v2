<?php
// Turn off error reporting
error_reporting(0);

require_once 'Config/index.php'; //Konfigirasyon tanımlamaları alınıyor
require_once 'Request/Request.php'; //Request için xml hazırlayan class ekleniyor
require_once 'Builder/Builder.php'; // İstek parametrelerini array olarak ayarlar

$request     = new Request();
$builder     = new Builder();

$data        = []; //Request parametrelerini saklıyor.

/**
 * Aşağıda örnek bir ödeme işlemi gerçekleşmektedir.
 */
//3DPay işlemi için gönderilecek olan xml parametreleri array olarak ayarlanıyor.
$parameters  = $builder->set3dRequestParametres();

//3DPay işlemi için gönderilecek olan xml request verisi hazırlanıyor
$request_xml = $request->set3dXml($parameters);

//3DPay işlemi servise gönderiliyor.
$response    = $request->sendRequest($request_xml,'secure3D');

