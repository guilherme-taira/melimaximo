<?php

namespace App\Http\Controllers\Hotmart\Services;

use App\Http\Controllers\Cliente\ClienteController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\RequestTemplete\requestTemplateController;
use Illuminate\Http\Request;

class GetOrderRequestController extends requestTemplateController
{
    const URL_BASE_HOTMART = "https://developers.hotmart.com/";



    public function resource(){
        return $this->get('payments/api/v1/sales/history?transaction_status=COMPLETE');
    }

    public function get($resource){

        // ENDPOINT PARA REQUISICAO
        $endpoint = self::URL_BASE_HOTMART.$resource;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $endpoint);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:application/json', 'Accept: application/json', "Authorization: Bearer H4sIAAAAAAAAAB2OS5KqMAAAT8QUQXRgOYOKiYYYEj5hQwGiBARRUSCnf77Zd3d1OaMqdwtJJGKBgsCT8Ak7f1k4cAWbPg4dZH%2BVM1LCsMeEwVU0ozmJtg2UozwtUH9yAxmzUSZxNcL6Nnm1ML01NggX6uCg6hT7t%2FyPu16L%2Bc%2BHHPgeDVEQ61vKGDp9uPvJbT4T0MBuIokLAWagEQaqD5wukigYCA9rj%2BkLT3mNp%2BgyqeEoeCA%2Fbp1%2F2nnnn7OI%2Fm9MnotN4gpAHP3zSs0D9ytcw0G0m8mbgcR1M%2BOamni9rQUX8ky%2FfPfosnaHdPC%2BubymtvCtXD9SGt5dGdkNtOwilrPTyKx%2FJMQEXbW1JpKZ%2BGksu9%2FWI6WFVsdeI6wbbH2XBbVK2SPXGH2ER0WF5gQ%2FRseDXeEP6%2B6Vr1GWBZvdPozVvvoeL7zd4lQB0NRguP0Ml6u7mUbmFnj5drouEFq1lWKKsdMao4nu5zte27L3L8aPISo4pPSWFsA%2BgNvI08p52WX61Mk8qHvKn93KInLU6KOlLbRaMx7CBZzcnu8fRsbe87ix8ESq9gV7U1rPeymLaGUGHv42WayIGjReHEddRNcSk5exakV5mMQGNdqL2ir7FYUM20P5Emo3n3Mw1u93c6IIX%2F4BlqoHSV4CAAA%3D"]);
        $response = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        echo "<pre>";
        $dados = json_decode($response);

        $trataDados = (new TrataDadosRequestHotmart($dados))->TrataDados();
        (new saveNewOrder)->saveNewOrder($trataDados);
    }
}
