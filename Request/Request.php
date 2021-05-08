<?php
require_once 'Response/Response.php';
/**
 * Class Request
 * Yapılacak olan Soap isteklerinin hazırlanmasını sağlar.
 */
class Request extends Response
{
    public $Hash             = '?op=SHA2B64&WSDL';
    public $secure3D         = '?op=Pos_Odeme&WSDL';
    public $installment      = '?op=TP_Ozel_Oran_Liste&WSDL';
    public $bin              = '?op=BIN_SanalPos&WSDL';

    /**
     * 3d secure rquest xml hazırlanıyor
     */
    public function set3dXml($parameters){
        $xml = file_get_contents('xml/3d.xml');
        foreach ($parameters as  $key=>$parameter){
            $xml = str_replace($key,$parameter,$xml);
        }
        return $xml;
    }

    /**
     *Taksit oranlarını çekecek olan xml alınıyor.
     */
    public function setInstallmentXml($parameters){
        $xml = file_get_contents('xml/installment.xml');
        foreach ($parameters as  $key=>$parameter){
            $xml = str_replace($key,$parameter,$xml);
        }
        return $xml;
    }

    /**
     * Bin verilerine göre kart bilgisini dönecek olan xml hazırlanıyor
     */
    public function setBinXml($parameters){
        $xml = file_get_contents('xml/bin.xml');
        foreach ($parameters as  $key=>$parameter){
            $xml = str_replace($key,$parameter,$xml);
        }
        return $xml;
    }

    public function sendRequest($xml,$type){
        try{

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL              => SERVICE_URL.$this->{$type},
                CURLOPT_RETURNTRANSFER   => true,
                CURLOPT_ENCODING         => '',
                CURLOPT_SSL_VERIFYPEER   => false,
                CURLOPT_MAXREDIRS        => 10,
                CURLOPT_TIMEOUT          => 0,
                CURLOPT_FOLLOWLOCATION   => true,
                CURLOPT_HTTP_VERSION     => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST    => 'POST',
                CURLOPT_POSTFIELDS       => $xml,
                CURLOPT_HTTPHEADER => array(
                    'Content-type: text/xml',
                ),
            ));

            $response = curl_exec($curl);

            $this->parseXml($response,$type);

            return $this->result;

        } catch(\Exception $e){
            return $e->getMessage();
        }
    }

}