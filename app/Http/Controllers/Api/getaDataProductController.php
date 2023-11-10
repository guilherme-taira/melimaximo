<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\DAO\sellerDao;
use App\Http\Controllers\SaleFeeAmount\getComissaoTaxaVenda;
use App\Http\Controllers\SaleFeeAmount\saleFeeAmountController;
use App\Http\Controllers\Service\GetDataSeller;
use App\Http\Controllers\Service\getGtinProduct;
use Illuminate\Http\Request;
use ZipArchive;

class getaDataProductController extends Controller
{
    public function getDataProduct(Request $request)
    {
        //sleep(1);
        // ENDPOINT PARA REQUISICAO
        $endpoint = "https://api.mercadolibre.com/items?ids=" . $request->idML;
        $array = [];
        //while (true) {
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $endpoint);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            $dados = json_decode($response);
            if ($httpcode == '200') {
                foreach ($dados as $value) {
                    // REQUISICAO PARA PEGAR DADOS DO SELLER
                    $DAO = new sellerDao();
                    $getDataSeller = new GetDataSeller($value->body->seller_id, $DAO);
                    $data = $getDataSeller->resource();
                    // PEGA O GTIN DE ATTRBUTES
                    $DATA = (new getGtinProduct());
                    // PEGA A PROVA SOCIAL
                    $Review = new GetDataSellerController;
                    $array['review'] = $Review->getItem($request->idML);
                    // PEGA O VALOR DAS TAXAS DOS PRODUTOS
                    $saleFeeController = new saleFeeAmountController();
                    $FeeAmount = new getComissaoTaxaVenda($value->body->price, $DATA->getAttributeTipoAnuncio($value->body->listing_type_id), $value->body->category_id, $saleFeeController);
                    $array['sale_fee_amount'] = $FeeAmount->getTafira()->getFeeAmount($FeeAmount->getPrice(), $FeeAmount->getListing_type_id(), $FeeAmount->getCategory_id()) != null ? $FeeAmount->getTafira()->getFeeAmount($FeeAmount->getPrice(), $FeeAmount->getListing_type_id(), $FeeAmount->getCategory_id()) : "N/D";
                    $array['GTIN'] = $DATA->getAttributeGtin($value->body->attributes);
                    $array['BRAND'] = $DATA->getAttributeBrand($value->body->attributes);
                    $array['empresa'] = $data->empresa == "undefined" ? "undefined" : $data->empresa;
                    $array['sold_quantity'] = $value->body->sold_quantity;
                    $array['power_seller_status'] = $data->power_seller_status;
                    $array['start_time'] = $value->body->start_time;
                    $array['price'] = $value->body->price;
                    $array['title'] = $value->body->title;
                    $array['category_id'] = $value->body->category_id;
                    $array['id'] = $value->body->id;
                    $array['listing_type_id'] = $DATA->getAttributeTipoAnuncio($value->body->listing_type_id);
                    $array['health'] = $value->body->health * 100;
                    $array['seller_address'] = $value->body->seller_address;
                    $array['attributes'] = $value->body->attributes;
                    $array['seller_id'] = $value->body->seller_id;
                    return response()->json($array);
                    // break;
                }
            } // else if ($httpcode == '429' || $httpcode == '500') {
            //     continue;
            // }
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 200);
        }
        // }
    }

    public function ManipulateData(Request $request)
    {
       return response()->json(['data' => $request->all()]);
    }

    public function getPhotos(Request $request)
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $requestData = json_decode(file_get_contents("php://input"), true);

            if (isset($requestData["images"])) {
                $zip = new ZipArchive();
                $zipFileName = "images.zip"; // Desired name of the ZIP file.

                foreach ($requestData["images"] as $index => $imageUrl) {
                    if ($zip->open($zipFileName, ZipArchive::CREATE)) {
                        $imageContent = file_get_contents($imageUrl);
                        $zip->addFromString(basename($imageUrl), $imageContent);
                    }
                }

                $zip->close();
                // Set appropriate headers to force download

                if (file_exists($zipFileName)) {

                    $demo_name = "fotos.zip";
                    header('Content-Type: application/zip');
                    header('Content-Disposition: attachment; filename="' . $demo_name . '"');

                    // Send the headers immediately
                    ob_clean(); // Clean any previously output content
                    flush();

                    readfile($zipFileName); // Send the file to the browser
                }
            } else {
                echo "No images received.";
            }
        } else {
            echo "Invalid request method.";
        }
    }
}
