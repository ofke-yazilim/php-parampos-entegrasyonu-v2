<?php
class Builder extends Request
{
    //Taksit oranlarını almak için gönderiledek parametreler
    public $dataInstallment= [
        '-CLIENT_CODE-'        => CLIENT_CODE,
        '-CLIENT_USERNAME-'    => CLIENT_USERNAME,
        '-CLIENT_PASSWORD-'    => CLIENT_PASSWORD,
        '-GUID-'               => GUID,
    ];

    //3d işelimi için gönderilecek parametreleri içerir
    public $data3d= [
        '-CLIENT_CODE-'        => CLIENT_CODE,
        '-CLIENT_USERNAME-'    => CLIENT_USERNAME,
        '-CLIENT_PASSWORD-'    => CLIENT_PASSWORD,
        '-GUID-'               => GUID,
        '-KK_Sahibi-'          => KK_Sahibi,
        '-KK_No-'              => KK_No,
        '-KK_SK_Ay-'           => KK_SK_Ay,
        '-KK_SK_Yil-'          => KK_SK_Yil,
        '-KK_CVC-'             => KK_CVC,
        '-KK_Sahibi_GSM-'      => KK_Sahibi_GSM,
        '-Hata_URL-'           => Hata_URL,
        '-Basarili_URL-'       => Basarili_URL,
        '-Siparis_ID-'         => Siparis_ID,
        '-Siparis_Aciklama-'   => Siparis_Aciklama,
        '-Taksit-'             => Taksit,
        '-Islem_Tutar-'        => '100,00',
        '-Toplam_Tutar-'       => '100,00',
        '-Islem_Hash-'         => '',
        '-Islem_Guvenlik_Tip-' => '3D',
        '-Islem_ID-'           => Siparis_ID."id",
        '-IPAdr-'              => IPAdr,
        '-Ref_URL-'            => 'https://dev.param.com.tr/tr',
        '-1a-'                 => '',
        '-2a-'                 => '',
        '-3a-'                 => '',
        '-4a-'                 => '',
        '-5a-'                 => '',
    ];


    /**
     * @return array
     * 3D Pay işelmleri için request parametreleri hazırlanıyor.
     */
    public function set3dRequestParametres(){
        $this->setHash();
        return $this->data3d;
    }

    /**
     * @return array
     * Taksit oranları için parametereler ayarlanıyor.
     */
    public function setInstallmentRateParametres(){
        return $this->dataInstallment;
    }

    /**
     * Hash datası için uygun string hazırlanıyor
     */
    public function setHash(){
        $Islem_Guvenlik_Str = $this->data3d['-CLIENT_CODE-'].$this->data3d['-GUID-'].$this->data3d['-Taksit-'].
                              $this->data3d['-Islem_Tutar-'].$this->data3d['-Toplam_Tutar-'].$this->data3d['-Siparis_ID-'].
                              $this->data3d['-Hata_URL-'].$this->data3d['-Basarili_URL-'];

        $this->data3d['-Islem_Hash-'] = $this->getHash($Islem_Guvenlik_Str);
    }

    /**
     * @param $Islem_Guvenlik_Str
     * @return string
     * hazılranmış olunan ilgili string adrese gönderiliyor.
     */
    public function getHash($Islem_Guvenlik_Str){
        $xml       = file_get_contents('xml/hash.xml');
        $response  = $this->sendRequest(str_replace('-Islem_Guvenlik_Str-',$Islem_Guvenlik_Str,$xml),'Hash');
        return $response;
    }

}