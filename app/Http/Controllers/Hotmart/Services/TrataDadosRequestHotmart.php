<?php

namespace App\Http\Controllers\Hotmart\Services;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use stdClass;

class TrataDadosRequestHotmart extends Controller
{
    private $response;

    public function __construct($response)
    {
        $this->response = $response;
    }

    public function TrataDados(): array
    {

        $i = 0;
        foreach ($this->response->items as $orders) {
            $data = [];
            $data[$i]['numeroPedido'] = $orders->purchase->transaction;
            $data[$i]['name'] = $orders->buyer->name;
            $data[$i]['email'] = $orders->buyer->email;
            $data[$i]['valor'] = $orders->purchase->price->value;
            $data[$i]['status'] = $orders->purchase->status;
            $data[$i]['dataAprovado'] = $orders->purchase->approved_date;
            $data[$i]['id'] = $orders->product->id;
            $data[$i]['produto'] = $orders->product->name;
            $i++;
        }

        return $data;
    }
}
