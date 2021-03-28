<?php

namespace App\Http\Controllers;

use App\Models\Carteira;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

/**
 * Class CarteiraController
 * @package App\Http\Controllers
 * @author Antonio Martins
 */
class CarteiraController extends GenericoController
{
    protected $model;

    /**
     * CarteiraController constructor.
     * @param Carteira $carteiraModel
     */
    public function __construct(Carteira $carteiraModel)
    {
        $this->model = $carteiraModel;
    }

    /**
     * Retorna um json que representa a carteira do usuário.
     *
     * @return JsonResponse
     * @author Antonio Martins
     */
    public function retornarCarteiraPorUsuario(): JsonResponse
    {
        try {
            $idUsuarioLogado = Auth::user()->id;
            $dados = $this->model
                ->where('id_usuario', '=', $idUsuarioLogado)
                ->where('deleted_at', '=', null)
                ->first();

            return response()->json($dados);
        } catch (Exception $e) {
            return response()->json(
                ['error' => 'Erro interno ao retornar carteira'],
                JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
