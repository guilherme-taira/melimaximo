<?php

namespace App\Http\Controllers\Hotmart\Services;

use App\Http\Controllers\Cliente\ClienteController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\RequestTemplete\requestTemplateController;
use App\Models\token;
use Illuminate\Http\Request;

class GetOrderRequestController extends requestTemplateController
{
    const URL_BASE_HOTMART = "https://developers.hotmart.com/";


    public function resource(){
        return $this->get('payments/api/v1/sales/history?transaction_status=APPROVED');
    }

    public function get($resource){

        // TOKEN PARA REQUISICAO
        $token = token::where('id',1)->first();
        // ENDPOINT PARA REQUISICAO
        $endpoint = self::URL_BASE_HOTMART.$resource;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $endpoint);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:application/json', 'Accept: application/json', "Authorization: Bearer {$token->access_token}"]);
        $response = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        echo "<pre>";
        $dados = json_decode($response);

        $trataDados = (new TrataDadosRequestHotmart($dados))->TrataDados();
        (new saveNewOrder)->saveNewOrder($trataDados);
    }
}
