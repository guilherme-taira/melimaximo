<?php

use App\Http\Controllers\Ajax\RastreioProdutoController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\getaDataProductController;
use App\Http\Controllers\Api\GetDataSellerController;
use App\Http\Controllers\Api\getNumberVisit;
use App\Http\Controllers\api\getProductById;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


//header('Access-Control-Allow-Origin: *');
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::prefix('v1')->group(function () {
    // ADD NEW USER
    Route::post('register',[AuthController::class,'register']);
    // LISTAR TODOS OS PEDIDOS
    Route::post('rastreios', [RastreioProdutoController::class, 'store']);
    Route::get('rastreios', [RastreioProdutoController::class, 'index']);
    // GET VISITAS
    Route::get('visitas', [getNumberVisit::class, 'index']);
    Route::get('visitasMounth', [getNumberVisit::class, 'getVisitsMounth']);
    Route::get('getMetricsMercadoLivre150days', [getNumberVisit::class, 'getMetricsMercadoLivre']);
    Route::get('getMetricsMercadoForGraphic', [getNumberVisit::class, 'getMetricsMercadoLivreGraphic']);
    Route::get('getSellerId', [UserController::class, 'getUserApiMl']);
    Route::post('getAllItem', [UserController::class, 'getAllItemApiMl']);
    Route::get('getItem',[GetDataSellerController::class,'getItem']);
    Route::get('getDataProduct',[getaDataProductController::class,'getDataProduct']);
    Route::get('getProductById',[getProductById::class,'getProduct']);
    Route::get('getDescription',[getProductById::class,'getDescription']);
    Route::get('getIntegrado',[getProductById::class,'verificaProdutoIntegrado']);
    Route::get('getImage',[getProductById::class,'pegaImagem']);
    Route::get('adrian',[UserController::class,'trocapalavra']);
    Route::post('zipper',[getaDataProductController::class,'getPhotos']);
    Route::get('verificaToken',[AuthController::class,'respondWithToken']);
    // Route::group(['middleware' => ['apiJwt']], function () {
    Route::group(['middleware' => ['auth:sanctum']], function () {
        // LISTAR USUARIOS
        Route::post('users/{id}', [UserController::class, 'show']);
    });
    // LOGAR NA PLATAFORMA
    Route::post('auth/login', [AuthController::class, 'login']);
});
