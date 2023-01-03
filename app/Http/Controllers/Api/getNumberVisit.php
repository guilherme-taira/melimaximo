<?php

namespace App\Http\Controllers\Api;

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


    public function getVisitsMounth(Request $request)
    {
        $endpoint = "https://api.mercadolibre.com/items/" . $request->id . "/visits/time_window?last=30&unit=day&ending=" . $request->date;
        /**
         * CURL REQUISICAO -X GET
         * **/
        while(true){
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
                }else if($httpcode == '429'){
                    continue;
                }
            } catch (\Exception $e) {
                //return response()->json($i);
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
                }else if($httpcode == '429'){
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
