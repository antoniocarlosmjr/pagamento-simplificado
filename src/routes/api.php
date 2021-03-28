<?php

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

Route::group(['namespace' => 'App\Http\Controllers'], function() {
    Route::post('usuario', 'UsuarioController@cadastrar');
    Route::post('login', 'AuthController@login');

    Route::group(['middleware' => ['apiJwt']], function() {
        Route::get('carteira', 'CarteiraController@retornarCarteiraPorUsuario');
        Route::post('transacao', 'TransacaoController@realizarTransferencia');
        Route::get('transacao', 'TransacaoController@retornarTransacoesPorUsuario');
    });
});


