<?php
class Builder extends Request
{
    //BIN numarasına göre  kart bilgisiniALMAK için gönderilecek parametreler
    public $dataBin= [
        '-CLIENT_CODE-'        => CLIENT_CODE,
        '-CLIENT_USERNAME-'    => CLIENT_USERNAME,
        '-CLIENT_PASSWORD-'    => CLIENT_PASSWORD,
        '-BIN-'                => '454671', //Test Bin numarası
    ];

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
    public function set3dRequestParametres($data = array()){
        foreach ($data as $key=>$value){
            $this->data3d[$key] = $value;
        }
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
     * @return array
     * Bin verilerine göre kart bilgilerini gönderecek data döndürülüyor.
     */
    public function setBinParametres($data = array()){
        foreach ($data as $key=>$value){
            $this->dataBin[$key] = $value;
        }
        return $this->dataBin;
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

    /**
     * @param $taksitOranlari
     * Gönderilen verilere uygun oalrak taksit tablosu inşa eder.
     */
    public function createRateTable($pos_id,$tutar){
        $table = '<style>
                table {
                  font-family: arial, sans-serif;
                  border-collapse: collapse;
                  width: 100%;
                  font-size: 11px;
                }
                
                td, th {
                  border: 1px solid #dddddd;
                  text-align: left;
                  padding: 4px;
                }
                
                tr:nth-child(even) {
                  background-color: #dddddd;
                }
                
                ._1{
                    text-align: right;
                }
                
                table input{
                    height: 15px;
                    width: 15px;
                    float:right;
                    margin-top: 10px;
                }
                </style>';

        $table .= '<table>
                  <tr>
                    <th class="_1">Taksit Sayısı</th>
                    <th>Oran</th>
                    <th>Taksit Tutarı</th>
                    <th>Toplam Ödenecek Ücret</th>
                  </tr>';

        $taksitOranlari = $this->getInstalmentRate($pos_id);
        foreach ($taksitOranlari as $key=>$value){
            if($key>0 && $value){
                $price  = 0;
                $price  = $tutar+($tutar*$value/100);
                $table .= '<tr>
                        <td class="_1"><input class="cpos_id" total="'.$price.'" type="radio" name="-Taksit-" value="'.$key.'" required/>'.$key.'</td>
                        <td>'.$value.'</td>
                        <td>'.number_format(round($price/$key,2),2).'</td>
                        <td>'.number_format(round($price,2),2).'</td>
                      </tr>';
            }
        }

        $table .= '</table>';

        return $table;
    }

    /**
     * @param $pos_id
     * @return mixed
     *
     * Verilen pos_id değeri için taksit verilerini alır
     */
    public function getInstalmentRate($pos_id){
        try {
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

            return $taksit_oranlari[$pos_id];
        } catch (\Exception $e){
            echo $e->getMessage();
        }

    }

}