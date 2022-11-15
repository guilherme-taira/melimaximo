<?php

namespace App\Http\Controllers\Hotmart;

use App\Http\Controllers\Controller;
use App\Models\token;
use DateTime;
use Illuminate\Http\Request;

interface RequestApi
{
    const URL_BASE = 'https://api-sec-vlc.hotmart.com/';
    public function get($resource);
    public function resource();
}

class AuthController implements RequestApi
{

    private $client_credentials;
    private $client_id;
    private $client_secrets;

    public function __construct($client_credentials, $client_id, $client_secrets)
    {
        $this->client_credentials = $client_credentials;
        $this->client_id = $client_id;
        $this->client_secrets = $client_secrets;
    }

    public function resource()
    {
        return $this->get("security/oauth/token?grant_type=client_credentials&client_id={$this->getClient_id()}&client_secret={$this->getClient_secrets()}");
    }

    public function get($resource)
    {
        // ENDPOINT PARA REQUISICAO
        $ENDPOINT = self::URL_BASE . $resource;

        $headers = array(
            "Content-Type: application/json",
            "Authorization: Basic {$this->getClient_credentials()}",
        );


        $token = token::where('user_id', $this->getClient_id())->first();

        // PEGA DATA ATUAL
        $datahoje = new DateTime();

        if(isset($token->datamodify)){
            $dataModificada = new DateTime($token->datamodify);
        }else{
            $dataModificada = new DateTime();
            $dataModificada->modify('-1 day');
        }


        if ($dataModificada < $datahoje) {
            try {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $ENDPOINT);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                $response = curl_exec($ch);
                $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);
                $dados = json_decode($response);

                $unixtime_to_date = new DateTime(date('Y-m-d H:i:s', $dados->expires_in));
                $datanova = new DateTime();
                // MODIFICA A DATA DAQUI 5 DIAS
                $datanova->modify('+5 days');

                if (isset($token)) {
                    token::where('user_id', $this->getClient_id())->update(['access_token' => $dados->access_token, 'datamodify' => $datanova->format('Y-m-d H:i:s')]);
                } elseif ($httpcode == 200) {
                    echo "CADASTRADO!";
                    $newToken = new token();
                    $newToken->access_token = $dados->access_token;
                    $newToken->type = $dados->token_type;
                    $newToken->refresh_token = $dados->jti;
                    $newToken->user_id = $this->getClient_id();
                    $newToken->datamodify = $datanova->format('Y-m-d H:i:s');
                    $newToken->save();
                }

                 return response()->json($dados);
            } catch (\Exception $e) {
                echo $e->getMessage();
                //return response()->json($i);
            }
        }
    }

    /**
     * Get the value of client_credentials
     */
    public function getClient_credentials()
    {
        return $this->client_credentials;
    }

    /**
     * Set the value of client_credentials
     *
     * @return  self
     */
    public function setClient_credentials($client_credentials)
    {
        $this->client_credentials = $client_credentials;

        return $this;
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

    /**
     * Get the value of client_secrets
     */
    public function getClient_secrets()
    {
        return $this->client_secrets;
    }

    /**
     * Set the value of client_secrets
     *
     * @return  self
     */
    public function setClient_secrets($client_secrets)
    {
        $this->client_secrets = $client_secrets;

        return $this;
    }
}
