<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\image\image;
use App\Models\products;
use Illuminate\Http\Request;

class getProductById extends Controller
{

    public function pegaImagem(Request $request)
    {

        $image = new image($request->image);
        return response()->json(["imagem" => $image->print()]);
    }

    public function verificaProdutoIntegrado(Request $request)
    {
        $data = products::where('id_mercadolivre', $request->id)->first();
        if ($data) {
            return response()->json(['response' => 1]);
        } else {
            return response()->json(['response' => 0]);
        }
    }

    public function getDescription($idproduto)
    {
        //ENDPOINT https://api.mercadolibre.com/items/$ITEM_ID/description
        // ENDPOINT PARA REQUISICAO
        $endpoint = "https://api.mercadolibre.com/items/$idproduto/description";

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
            return $dados->plain_text;
        }
    }

    public function getProduct(Request $request)
    {
        $array = [];
        // ENDPOINT PARA REQUISICAO
        $endpoint = 'https://api.mercadolibre.com/items/' . $request->id;

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
                $produto = new products();
                $produto->id_mercadolivre = $dados->id;
                $produto->title = $dados->title;
                $produto->description = $this->getDescription($request->id);
                $produto->price = $dados->price;
                $produto->available_quantity = $dados->initial_quantity;
                $produto->category_id = $dados->category_id;
                $produto->listing_type_id = $dados->listing_type_id;
                $produto->buying_mode = $dados->buying_mode;
                $produto->condition = $dados->condition;
                $produto->currency_id = $dados->currency_id;
                $produto->isKit = 0;
                $produto->subcategoria = 2;
                $produto->isPublic = 1;
                $produto->fornecedor_id = 3;
                $produto->height = 1;
                $produto->width = 1;
                $produto->image = "";
                $produto->length = 1;
                $produto->weight = 1;
                $produto->isMercadoLivre = 1;
                $produto->imageJson = json_encode($dados->pictures);
                $produto->termometro = $dados->health * 100;
                foreach ($dados->attributes as $attribute) {

                    if ($attribute->id == "BRAND") {
                        $produto->brand = isset($attribute->value_name) ? $attribute->value_name : "N/D";
                    } else {
                        $produto->brand = "N/D";
                    }

                    if ($attribute->id == "GTIN") {
                        $produto->gtin = $attribute->value_name;
                    } else {
                        $produto->gtin = "000";
                    }
                }
                $produto->save();
                $array['code'] = 200;
                return response()->json($array);
            } else if ($httpcode == '429' || $httpcode == '500') {
                $array['error'] = "ERRO 429";
            }
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    public function getProductByIdGraphic(Request $request)
    {
        $array = [];
        // ENDPOINT PARA REQUISICAO
        $endpoint = 'https://api.mercadolibre.com/items/' . $request->id;

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
            return $dados;
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }
}
