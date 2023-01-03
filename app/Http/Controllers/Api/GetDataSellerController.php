<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\RequestTemplete\requestTemplateController;
use Illuminate\Http\Request;

class GetDataSellerController extends Controller
{

    public function getItem(String $id = "ND")
    {

        // ENDPOINT PARA REQUISICAO
        $endpoint = 'https://api.mercadolibre.com/reviews/item/' . $id;
        $array = [];
        while (true) {
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
                    $OneStar = $dados->rating_levels->one_star;
                    $TwoStar = $dados->rating_levels->two_star;
                    $ThreeStar =$dados->rating_levels->three_star;
                    $FourStar =$dados->rating_levels->four_star;
                    $FiveStar = $dados->rating_levels->five_star;
                    $reviews = $dados->reviews;
                    $array = compact('OneStar','TwoStar','ThreeStar','FourStar','FiveStar','reviews');
                    return $array;
                    break;
                } else if ($httpcode == '429' || $httpcode == '500') {
                    $array['error'] = "ERRO 429";
                    continue;
                }

                return $array;
            } catch (\Exception $e) {
                //return response()->json($i);
                continue;
            }
        }
    }
}
