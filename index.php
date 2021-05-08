<?php
/**
 * @okesmez
 * http://okesmez.com/
 * last Updated : 08.05.2021 11:50
 *
 */

// Turn off error reporting
error_reporting(0);

require_once 'Config/index.php'; //Konfigirasyon tanımlamaları alınıyor
require_once 'Request/Request.php'; //Request için xml hazırlayan class ekleniyor
require_once 'Builder/Builder.php'; // İstek parametrelerini array olarak ayarlar

$request     = new Request();
$builder     = new Builder();

$data        = [
    '-KK_Sahibi-'          => 'okesmez',
    '-KK_No-'              => '4022774022774026',
    '-KK_SK_Ay-'           => 12,
    '-KK_SK_Yil-'          => 26,
    '-KK_CVC-'             => '000',
    '-KK_Sahibi_GSM-'      => '',
    '-Siparis_ID-'         => time(),
    '-Siparis_Aciklama-'   => 'Test Siparişi',
    '-Taksit-'             => 1, //1,2,3,5....
    '-Islem_Tutar-'        => '12,00',
    '-Toplam_Tutar-'       => '12,00',
    '-Islem_ID-'           => 'islemId'.time(),
    '-IPAdr-'              => $_SERVER['REMOTE_ADDR'],
    '-1a-'                 => 'Test Data 1',
    '-2a-'                 => 'Test Data 2',
    '-3a-'                 => 'Test Data 3',
];

if (!empty($_POST)) {
    foreach ($_POST as $key=>$value){
        if($key == '-KK_SK_Yil-'){
            $value = substr($value,-2);
        }

        if($key == '-Islem_Tutar-'){
            $value                  = number_format($value,2,",",".");
            $data['-Toplam_Tutar-'] = number_format($value,2,",",".");
        }

        if($key == 'total'){
            $data['-Islem_Tutar-']  = number_format($value,2,",",".");
            $data['-Toplam_Tutar-'] = number_format($value,2,",",".");
        }

        $data[$key] = $value;
    }

    unset($data['total']);
    unset($data['pos_id']);
}

/**
 * Aşağıda örnek bir ödeme işlemi gerçekleşmektedir.
 */
//3DPay işlemi için gönderilecek olan xml parametreleri array olarak ayarlanıyor.
$parameters  = $builder->set3dRequestParametres($data);

//3DPay işlemi için gönderilecek olan xml request verisi hazırlanıyor
$request_xml = $request->set3dXml($parameters);

//3DPay işlemi servise gönderiliyor.
$response    = $request->sendRequest($request_xml,'secure3D');

