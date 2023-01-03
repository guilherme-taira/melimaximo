<?php

namespace App\Http\Controllers\SaleFeeAmount;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class saleFeeAmountController extends Controller
{

    public function getFeeAmount($price, $listing_type_id, $category_id)
    {
        // ENDPOINT PARA REQUISICAO
        $endpoint = "https://api.mercadolibre.com/sites/MLB/listing_prices?price=" . $price . "&listing_type_id=" . $listing_type_id . "&category_id=" . $category_id;
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $endpoint);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            $dados = json_decode($response);
            if ($httpcode == '200') {
                foreach ($dados as $dado) {
                    return $dado->sale_fee_amount;
                }
            }
        } catch (\Exception $e) {
            //return response()->json($i);

        }
    }
}
