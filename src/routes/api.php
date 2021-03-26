<?php

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
/** @var Route $router */

$router->post('usuario', 'UsuarioController@criarUsuario');
$router->post('autenticacao', 'AuthController@login');

$router->group(['middleware' => 'api'], function() use ($router) {
    $router->group(['prefix' => 'carteira'], function () use ($router) {
        $router->get('', 'CarteiraController@index');
    });

    $router->group(['prefix' => 'transacoes'], function () use ($router) {
        $router->get('', 'TransacaoController@index');
        $router->get('{id}', 'TransacaoController@show');
        $router->post('', 'TransacaoController@realizarTransferencia');
    });
});

