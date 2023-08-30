<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\DAO\sellerDao;
use App\Http\Controllers\Service\GetDataSeller;
use App\Models\User;
use DateTime;
use Illuminate\Http\Request;
use Laravel\Sanctum\Sanctum;

header("Access-Control-Allow-Methods: GET, POST, PUT, OPTIONS");
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $users = User::all();
        return response()->json(['dados' => $users, 'token' => $request->all()]);
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
    public function show($id, Request $request)
    {
        $users = User::where('id', $id)->first();

        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );

        if(!$users){
            return response()->json(["msg" => "Credencias Inválidas!"],200);
        }
        return response()->json($users);
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

    public function getUserApiMl(Request $request)
    {
        $endpointMetrics = "https://api.mercadolibre.com/users/$request->sellerId";
        $array = [];

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
            $array['power_seller_status'] = isset($dados->seller_reputation->power_seller_status) ? $dados->seller_reputation->power_seller_status : "DESCONHECIDO";
            $array['empresa'] = isset($dados->nickname) ? $dados->nickname : "N/D";
            return response()->json($array);
        } else if ($httpcode == '429') {
            $array['power_seller_status'] = "DESCONHECIDO";
            $array['empresa'] = "N/D";
            return response()->json($array, 200);
        }
    }


    public function trocapalavra(Request $request){
        return response()->json(["respostaAdrian" => "<b>$request->texto</b>"]);
    }

    public function getAllItemApiMl(Request $request)
    {
        $i = 0;
        $array = [];
        while (true) {
            try {
                foreach ($request->data as $produto) {
                    $endpointMetrics = "https://api.mercadolibre.com/items/" . $produto;
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
                    $dataAtual = new DateTime();
                    if ($httpcode == '200') {
                        $DAO = new sellerDao();
                        $getDataSeller = new GetDataSeller($dados->seller_id, $DAO);
                        $seller = $getDataSeller->resourceGetData();

                        $data = new GetDataSellerController();
                        $date = new getNumberVisit();
                        array_push($array,["data" => $dados, "dadosproduto" => $data->getItem($produto), "seller" => $seller,"id" => $produto]);
                        $i++;
                    } else if ($httpcode == '429' || $httpcode == '500') {
                        $array['error'] = "ERRO 429";
                        continue;
                    }
                    if ($i == 56) {
                        break;
                    }
                }
                return response()->json(["dados" => $array]);
            } catch (\Exception $e) {
                // return response()->json($produto);
                 continue;
            }
        }
    }
}
