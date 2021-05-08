<?php
/*
 * Proje url adresi alınıyor.
 */
define('URL',(isset($_SERVER["HTTPS"]) ? 'https' : 'http').'://'.$_SERVER['HTTP_HOST']);

/**
 * İstek adresleri tanımlanıyor
 */
define("WHICH","TEST"); // Hangi ortam çalıaşcak buradn belirliyoruz. TEST or PROD

if(WHICH == "TEST"){
    define("SERVICE_URL",'https://posws.param.com.tr/turkpos.ws/service_turkpos_prod.asmx');
} else{
    define("SERVICE_URL","https://test-dmz.param.com.tr:4443/turkpos.ws/service_turkpos_test.asmx");
}

/**
 * @okesmez
 * 07.05.2021
 * Parampos işletme bilgileri tanımlanıyor.
 * http://okesmez.com/
 */
define("CLIENT_CODE","10738");
define("CLIENT_USERNAME","Test");
define("CLIENT_PASSWORD","Test");
define("CLIENT_PASSWORD","Test");
define("GUID","0c13d406-873b-403b-9c09-a5766840d98c");

/**
 * Başarılı ve başarısız sayfaları.
 */
define("Hata_URL",URL."/parampos-v2/view/basarili.php");
define("Basarili_URL",URL."/parampos-v2/view/hatali.php");

/**
 * Aşağıda örnek test kartı bilgileri hazırlanıyor.
 */
define("KK_Sahibi","Test");
define("KK_No","4022774022774026");
define("KK_SK_Ay","12");
define("KK_SK_Yil","26");
define("KK_CVC","000");
define("KK_Sahibi_GSM","5078896946");

define("Siparis_ID",time());
define("Siparis_Aciklama","Test Siparişi");
define("Taksit",1);
define("IPAdr",$_SERVER['REMOTE_ADDR']);