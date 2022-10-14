<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Models\rastreio;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Contracts\Service\Attribute\Required;

class RastreioProdutoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dados = rastreio::all();
        return response()->json(['dados' => $dados],200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'estoque' => 'required|numeric',
            'foto' => 'required',
            'codigo' => 'Required|max:14',
            'preco' => 'Required|numeric',
        ]);

        $errors = $validator->errors();

        if ($validator->fails()) {
            return response()->json($errors,422);
        }

        // DATA ATUAL
        $dataAtual = new DateTime();

        $newRastreio = new rastreio([
            'name' => $request->name,
            'estoque' => $request->estoque,
            'foto' => $request->foto,
            'codigo' => $request->codigo,
            'preco' => $request->preco,
            'dataVerificada' => $dataAtual->format('Y-m-d H:i:s'),
        ]);

        $newRastreio->save();

        return response()->json($newRastreio,200);
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

}
