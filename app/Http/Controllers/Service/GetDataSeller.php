<?php

namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;
use App\Http\Controllers\DAO\sellerDao;
use App\Http\Controllers\RequestTemplete\requestTemplateController;
use Illuminate\Http\Request;

class GetDataSeller extends requestTemplateController
{

    private $sellerId;
    private sellerDao $DAO;


    const URL_BASE_ML = "https://api.mercadolibre.com/";

    public function __construct($sellerId,sellerDao $DAO)
    {
        $this->sellerId = $sellerId;
        $this->DAO = $DAO;
    }

    public function resource(){
        return $this->get("users/".$this->getSellerId());
    }

    public function get($resource){
        $endpointMetrics = self::URL_BASE_ML.$resource;
        $array = [];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $endpointMetrics);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        $dados = json_decode($response);
        if ($httpcode == '200') {
            // SETA AS VARIAVEIS NA CLASSE DAO
            $this->DAO->setPower_seller_status($dados->seller_reputation->power_seller_status);
            $this->DAO->setEmpresa( $dados->nickname);

            $array['power_seller_status'] = $this->DAO->getPower_seller_status();
            $array['empresa'] = $this->DAO->getEmpresa();

            return json_decode(json_encode($array));
        } else if ($httpcode == '429') {
            $array['power_seller_status'] = "DESCONHECIDO";
            $array['empresa'] = "N/D";
            return json_decode(json_encode($array));
        }
    }


    public function resourceGetData(){
        return $this->getSellerData("users/".$this->getSellerId());
    }

    public function getSellerData($resource){
        $endpointMetrics = self::URL_BASE_ML.$resource;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $endpointMetrics);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        $dados = json_decode($response);
        if ($httpcode == '200') {
            // SETA AS VARIAVEIS NA CLASSE DAO
            return json_decode(json_encode($dados));
        }
    }
    /**
     * Get the value of sellerId
     */
    public function getSellerId()
    {
        return $this->sellerId;
    }

    /**
     * Set the value of sellerId
     *
     * @return  self
     */
    public function setSellerId($sellerId)
    {
        $this->sellerId = $sellerId;

        return $this;
    }
}
