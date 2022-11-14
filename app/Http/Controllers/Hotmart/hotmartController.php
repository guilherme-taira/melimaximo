<?php

namespace App\Http\Controllers\Hotmart;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Hotmart\Services\GetOrderRequestController;
use Illuminate\Http\Request;

class hotmartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // echo "<pre>";
        // (new AuthController('MTRjODhmNTgtMmVkOC00YTIyLWJhMTctYjI5MjI0ODAxY2E2OjlmYjc0YzdlLTkwZTYtNDkxZi04ZTI0LTE3NzM1ZWJiNTQxZg==','14c88f58-2ed8-4a22-ba17-b29224801ca6','9fb74c7e-90e6-491f-8e24-17735ebb541f'))->resource();

        // $getOrder = new getAllOrderHotmart('14c88f58-2ed8-4a22-ba17-b29224801ca6');
        // print_r($getOrder->resource());

        $getOrder = (new GetOrderRequestController())->resource();
        print_r($getOrder);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
