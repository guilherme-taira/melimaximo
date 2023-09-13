<?php

namespace App\Http\Controllers\Hotmart;

use App\Http\Controllers\Controller;
use App\Models\token;
use Illuminate\Http\Request;
use App\Http\Controllers\Hotmart\hotmartController;

class getAllOrderHotmart implements RequestApi
{

    const URL_BASE_ORDER = 'https://developers.hotmart.com/';

    private $client_id;

    public function resource()
    {
        return $this->get('payments/api/v1/sales/history?transaction_status=APPROVED');
    }

    public function __construct($client_id)
    {
        $this->client_id = $client_id;
    }

    public function get($resource)
    {

        // ENDPOINT PARA REQUISICAO
        $ENDPOINT = self::URL_BASE_ORDER.$resource;

        $getToken = token::where('user_id',$this->getClient_id())->first();
        $token = $getToken->access_token;
        $headers = array(
            "Content-Type: application/json",
            "Authorization: Bearer $token",
        );

        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $ENDPOINT);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            $response = curl_exec($ch);
            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if($httpcode == 401){
                $auth = (new AuthController('Basic MTRjODhmNTgtMmVkOC00YTIyLWJhMTctYjI5MjI0ODAxY2E2OjlmYjc0YzdlLTkwZTYtNDkxZi04ZTI0LTE3NzM1ZWJiNTQxZg==','14c88f58-2ed8-4a22-ba17-b29224801ca6','9fb74c7e-90e6-491f-8e24-17735ebb541f'))->resource();
            }

            $dados = json_decode($response);

            return response()->json($dados);

        } catch (\Exception $e) {
            echo $e->getMessage();
            //return response()->json($i);
        }
    }

    /**
     * Get the value of client_id
     */
    public function getClient_id()
    {
        return $this->client_id;
    }

    /**
     * Set the value of client_id
     *
     * @return  self
     */
    public function setClient_id($client_id)
    {
        $this->client_id = $client_id;

        return $this;
    }
}
