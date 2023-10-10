<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\api\getProductById;
use App\Http\Controllers\Controller;
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

    // Check for cURL errors.
    if (curl_errno($curl)) {
        echo 'cURL error: ' . curl_error($curl);
        // Handle the error as needed.
    } else {
        // HTTP request was successful.

        // You can now work with the response data, for example, echo it:
         print_r($response);
    }
    }

    public function information(Request $request){

        // Specify the URL of the endpoint you want to request.
        $input = file_get_contents('php://input');
        $array = json_decode($input,false);

        // Initialize cURL session.
        $curl = curl_init();

        // Set cURL options.
        curl_setopt($curl, CURLOPT_URL, $array->link);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        // Execute the cURL session to make the request.
        $response = curl_exec($curl);

        // Check for cURL errors.
        if (curl_errno($curl)) {
            echo 'cURL error: ' . curl_error($curl);
            // Handle the error as needed.
        } else {
        // HTTP request was successful.

        // You can now work with the response data, for example, echo it:
        print_r($response);
        }
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
