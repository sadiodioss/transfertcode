<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\CompteController;
use App\Http\Controllers\TransactionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/compte/{chaine}',[ClientController::class,'getCompte']);
Route::post('/depot',[TransactionController::class,'depot']);
Route::get('/compte',[CompteController::class,'index']);
Route::get('/transaction/compte/{compte}',[TransactionController::class,'getAllTransacFilter']);

 Route::post('/transfert',[TransactionController::class,'transfert']);
 Route::delete('/compte/{compte}',[CompteController::class,'destroy']);

 Route::post('/ajout',[ClientController::class,'store']);
 Route::post('/crecompte',[CompteController::class,'store']);
 //route blocage et deblocage de compte//
 Route::put('/bloque/{compte}',[CompteController::class,'bloque']);
 Route::put('/debloque/{compte}',[CompteController::class,'debloque']);
 Route::put('/annuler',[TransactionController::class,'deletTransaction']);


