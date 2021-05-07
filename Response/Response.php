<?php

/**
 * Class Response
 * Servisten alınan xml verileri parse ediliyor.
 */
class Response
{
    public $hash_filter        = "'<SHA2B64Result>(.*?)</SHA2B64Result>'si";
    public $secure3D_filter    = "'<ucd_url>(.*?)</ucd_url>'si";
    public $installment_filter = "'<DT_Ozel_Oranlar(.*?)</DT_Ozel_Oranlar>'si";

    public $result          = '';

    /**
     * @param $responseXml
     * @param $type
     * $responseXml soap servisinden dönen xml içeirğidir.
     * $type hangi servise ait içerik olduğunu belirtir.
     */
    public function parseXml($responseXml,$type){
        $this->$type($responseXml);

    }

    public function Hash($responseXml){
        preg_match($this->hash_filter, $responseXml, $match);
        //var_dump($match);exit;

        $this->result = $match[1];
        return $match[1];
    }

    public function secure3D($responseXml){
        preg_match($this->secure3D_filter, $responseXml, $match);

        $this->result = $match[1];

        $view  = str_replace('-url-',$match[1],file_get_contents('view/redirect.html'));
        echo $view;
        exit;
        //echo '<a href="'.$match[1].'">'.$match[1].'</a>';
        //header("Location: ".$match[1]);
    }

    public function installment($responseXml){
        $installment = [];
        preg_match_all($this->installment_filter, $responseXml, $matches);
        foreach ($matches[1] as $match){
            $pos_id                  = preg_match("'<sanalpos_id>(.*?)</sanalpos_id>'si", $match, $values);
            $pos_id                  = $values[1];

            $data                    = preg_match("'<kredi_karti_banka>(.*?)</kredi_karti_banka>'si", $match, $values);
            $installment[$pos_id][]  = $values[1];
            $data                    = preg_match("'<mo_01>(.*?)</mo_01>'si", $match, $values);
            $installment[$pos_id][]  = $values[1];
            $data                    = preg_match("'<mo_02>(.*?)</mo_02>'si", $match, $values);
            $installment[$pos_id][]  = $values[1];
            $data                    = preg_match("'<mo_03>(.*?)</mo_03>'si", $match, $values);
            $installment[$pos_id][]  = $values[1];
            $data                    = preg_match("'<mo_04>(.*?)</mo_04>'si", $match, $values);
            $installment[$pos_id][]  = $values[1];
            $data                    = preg_match("'<mo_05>(.*?)</mo_05>'si", $match, $values);
            $installment[$pos_id][]  = $values[1];
            $data                    = preg_match("'<mo_06>(.*?)</mo_06>'si", $match, $values);
            $installment[$pos_id][]  = $values[1];
            $data                    = preg_match("'<mo_07>(.*?)</mo_07>'si", $match, $values);
            $installment[$pos_id][]  = $values[1];
            $data                    = preg_match("'<mo_08>(.*?)</mo_08>'si", $match, $values);
            $installment[$pos_id][]  = $values[1];
            $data                    = preg_match("'<mo_09>(.*?)</mo_09>'si", $match, $values);
            $installment[$pos_id][]  = $values[1];
            $data                    = preg_match("'<mo_10>(.*?)</mo_10>'si", $match, $values);
            $installment[$pos_id][]  = $values[1];
            $data                    = preg_match("'<mo_11>(.*?)</mo_11>'si", $match, $values);
            $installment[$pos_id][]  = $values[1];
            $data                    = preg_match("'<mo_12>(.*?)</mo_12>'si", $match, $values);
            $installment[$pos_id][]  = $values[1];
            $data                    = preg_match("'<mo_13>(.*?)</mo_13>'si", $match, $values);
            $installment[$pos_id][]  = $values[1];
            $data                    = preg_match("'<mo_14>(.*?)</mo_14>'si", $match, $values);
            $installment[$pos_id][]  = $values[1];
            $data                    = preg_match("'<mo_15>(.*?)</mo_15>'si", $match, $values);
            $installment[$pos_id][]  = $values[1];
            $data                    = preg_match("'<mo_16>(.*?)</mo_16>'si", $match, $values);
            $installment[$pos_id][]  = $values[1];
        }

        $this->result = $installment;
        return $installment;
    }

}