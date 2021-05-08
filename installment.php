<?php

// Turn off error reporting
error_reporting(0);

require_once 'Config/index.php'; //Konfigirasyon tanımlamaları alınıyor
require_once 'Request/Request.php'; //Request için xml hazırlayan class ekleniyor
require_once 'Builder/Builder.php'; // İstek parametrelerini array olarak ayarlar

$request = new Request();
$builder = new Builder();

/**
 * Aşağıda taksit oranlarını çekecek olan sorgulamalar bulunmaktadır.
 */
//Taksit oranlarını çekmek için gönderilecek olan xml parametreleri array olarak ayarlanıyor.
$parameters = $builder->setInstallmentRateParametres();
//Taksit oranlarını çekmek için gönderilecek olan request verisi hazırlanıyor
$request_xml = $request->setInstallmentXml($parameters);
//Taksit oranlarını çekmek için gönderiliyor.
$response = $request->sendRequest($request_xml, 'installment');
//Taksit oranları array olarak alınıyor.
$taksit_oranlari = $request->result;

echo '<pre>';
print_r($taksit_oranlari);
echo '</pre>';

