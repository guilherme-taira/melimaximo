<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\api\getProductById;
use App\Http\Controllers\Controller;
use DOMDocument;
use Illuminate\Http\Request;

class getNumberVisit extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $dados = json_encode($this->getVisits($request->ids, $request->date));
        return response()->json($dados);
    }

    public function informationPagination(){
            // Specify the URL of the endpoint you want to request.
    $endpointUrl = 'https://lista.mercadolivre.com.br/alimentos-bebidas/mercearia/chocolate_Desde_51_NoIndex_True';

    // Initialize cURL session.
    $curl = curl_init();

    // Set cURL options.
    curl_setopt($curl, CURLOPT_URL, $endpointUrl);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    // Execute the cURL session to make the request.
    $response = curl_exec($curl);

    $document = new \DOMDocument('1.0', 'UTF-8');
    // set error level
    $internalErrors = libxml_use_internal_errors(true);
    // load HTML
    $document->loadHTML($response);
    // Restore error level
    libxml_use_internal_errors($internalErrors);
    $modifiedHtml = $document->saveHTML();


    // Check for cURL errors.
    if (curl_errno($curl)) {
        echo 'cURL error: ' . curl_error($curl);
        // Handle the error as needed.
    } else {
        // HTTP request was successful.
        print_r($modifiedHtml);
    }
    }

    public function information(Request $request){

        // Specify the URL of the endpoint you want to request.
        $input = file_get_contents('php://input');
        $array = json_decode($input,false);
        $arrayHtml = [];

        $novoNumero = 50;
        for ($index = 1; $index <= $array->pagina; $index++) {

        // Initialize cURL session.
        $curl = curl_init();

        // Set cURL options.
        curl_setopt($curl, CURLOPT_URL, $this->removerNumeroNaURL($array->link,$novoNumero));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        // Execute the cURL session to make the request.
        $response = curl_exec($curl);
        // Check for cURL errors.
        if (curl_errno($curl)) {
            echo 'cURL error: ' . curl_error($curl);
            // Handle the error as needed.
        } else {
        // You can now work with the response data, for example, echo it:
            $document = new \DOMDocument('1.0', 'UTF-8');
            // set error level
            $internalErrors = libxml_use_internal_errors(true);
            // load HTML
            $document->loadHTML($response);
            // Restore error level
            libxml_use_internal_errors($internalErrors);
            $modifiedHtml = $document->saveHTML();
            array_push($arrayHtml,$modifiedHtml);
        }
          $novoNumero += 50;
        }
        return json_encode($arrayHtml);

    }

    function removerNumeroNaURL($url,$novoNumero) {
        $newValue = 51 + $novoNumero;
        if($novoNumero == 51){
            $newValue = 51;
        }
        // Substitua "51" por uma string vazia para remover o número
        $novaURL = str_replace("51", $newValue, $url);

        return $novaURL;
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getVisits($id, $date)
    {

        $endpoint = "https://api.mercadolibre.com/items/" . $id . "/visits/time_window?last=7&unit=day&ending=" . $date;
        /**
         * CURL REQUISICAO -X GET
         * **/
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $endpoint);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response);
    }

    public function getVisits30Days($id)
    {
        $endpoint = "https://api.mercadolibre.com/items/" . $id . "/visits/time_window?last=30&unit=day&ending=2023-06-01";
        /**
         * CURL REQUISICAO -X GET
         * **/
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
                $array = [];
                if ($httpcode == '200') {
                    $array['id'] = $id;
                    $array['total'] = $dados->total_visits;
                    return response()->json($array);
                    break;
                } elseif ($httpcode == '429') {
                    continue;
                }
            } catch (\Exception $e) {
                //return response()->json($i);
                continue;
            }
        }
    }

    public function getVisitsMounth(Request $request)
    {
        $endpoint = "https://api.mercadolibre.com/items/" . $request->id . "/visits/time_window?last=30&unit=day&ending=" . $request->date;
        /**
         * CURL REQUISICAO -X GET
         * **/
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
                $array = [];
                if ($httpcode == '200') {
                    $array['id'] = $request->id;
                    $array['total'] = $dados->total_visits;
                    return response()->json($array);
                    break;
                } else if ($httpcode == '429') {
                    continue;
                }
            } catch (\Exception $e) {
                //return response()->json($i);
                continue;
            }
        }
    }

    public function getMetricsMercadoLivreGraphic(Request $request)
    {
        $endpointMetrics = "https://api.mercadolibre.com/items/" . $request->id . "/visits/time_window?last=150&unit=day";
        /**
         * CURL REQUISICAO -X GET
         * **/
        while (true) {
            try {
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
                    $data = new getProductById();
                    return response()->json(["visitas" => $dados], ["data" => $data->getProductById($request->id)]);
                    break;
                } else if ($httpcode == '429') {
                    continue;
                }
            } catch (\Exception $e) {
                //return response()->json($i);
                echo $e->getMessage();
                continue;
            }
        }
    }

    public function getMetricsMercadoLivre(Request $request)
    {

        $endpointMetrics = "https://api.mercadolibre.com/items/" . $request->id . "/visits/time_window?last=150&unit=day";
        /**
         * CURL REQUISICAO -X GET
         * **/
        while (true) {
            try {
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
                    return response()->json($dados);
                    break;
                } else if ($httpcode == '429') {
                    continue;
                }
            } catch (\Exception $e) {
                //return response()->json($i);
                echo $e->getMessage();
                continue;
            }
        }

    }

}
