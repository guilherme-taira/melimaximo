<?php

use App\Http\Controllers\Hotmart\hotmartController;
use App\Http\Controllers\View\ProductMetrictsController;
use App\Http\Controllers\Hotmart\Services\produtosHotmartController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// ROTAS GET
Route::get('/metrics',[ProductMetrictsController::class,'getMetrics'])->name('metrics');
Route::get('politicas',[ProductMetrictsController::class,'politicas'])->name('politica');
// ROTAS RESOURCE
Route::resource('hotmart','App\Http\Controllers\Hotmart\hotmartController')->names('hotmart')->parameters(['hotmart' => 'id']);
Route::resource('produtos','App\Http\Controllers\Hotmart\Services\produtosHotmartController')->names('produtos')->parameters(['produtos' => 'id']);
