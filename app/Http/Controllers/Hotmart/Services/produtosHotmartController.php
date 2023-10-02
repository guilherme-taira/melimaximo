<?php

namespace App\Http\Controllers\Hotmart\Services;

use App\Http\Controllers\Controller;
use App\Models\items;
use App\Models\products;
use App\Models\produtos_prazo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class produtosHotmartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $produtos = products::getCompleteProduct();

        return view('produtos.index',[
            'data' => $produtos
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $produtos = products::all();

        return view('produtos.create',[
            'produtos' => $produtos
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validated = Validator::make($request->all(),[
            'prazo' => 'required',
            'produto' => 'required',
            'plano' => 'required'
        ]);

        if ($validated->fails()) {
            return redirect()->back()
                ->withErrors($validated)
                ->withInput();
        }

        $data = new produtos_prazo();
        $data->prazo = $request->prazo;
        $data->product_id = $request->produto;
        $data->plan = $request->plano;
        $data->save();
        return redirect()->route('produtos.index');

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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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
