<?php

namespace App\Http\Controllers\View;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductMetrictsController extends Controller
{
    public function getMetrics(Request $request){

        return view('layouts',['id' => $request->all()]);
    }
}

