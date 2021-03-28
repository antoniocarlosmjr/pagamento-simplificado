<?php

namespace App\Http\Controllers;

use App\Exceptions\TransacaoException;
use App\Models\Transacao;
use App\Models\User;
use App\Events\TransferenciaRecebida;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;

/**
 * Class TransacaoController
 * @package App\Http\Controllers
 * @author Antonio Martins
 */
class TransacaoController extends GenericoController
{
    protected $model;

    /**
     * CarteiraController constructor.
     * @param Transacao $transacaoModel
     */
    public function __construct(Transacao $transacaoModel)
    {
        $this->model = $transacaoModel;
    }

    /**
     * Realiza a transferência de um valor para um determinado usuário.
     *
     * @param Request $request
     * @return JsonResponse
     * @author Antonio Martins
     */
    public function realizarTransferencia(Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $request->validate($this->model->regras(), $this->model->mensagens());

            $usuarioPagadorModel = User::find($request->id_usuario_pagador);
            $usuarioBeneficiarioModel = User::find($request->id_usuario_beneficiario);
            $this->model->setAttribute('valor', (float) $request->valor);

            $this->model = $this->model->transferencia(
                $usuarioPagadorModel,
                $usuarioBeneficiarioModel,
                $this->model
            );

            $this->model->create($request->all());
            event(new TransferenciaRecebida($usuarioBeneficiarioModel));
            DB::commit();

            return response()->json(
                ['success' => 'Transferência realizada com sucesso!'],
            JsonResponse::HTTP_CREATED
            );
        }  catch (TransacaoException $error) {
            DB::rollBack();
            return response()->json(
                [
                    'message' => "Erro ao tentar realizar transferência",
                    'error' => $error->getMessage()
                ],
                JsonResponse::HTTP_UNPROCESSABLE_ENTITY
            );
        } catch (Throwable $error) {
            DB::rollBack();
            return response()->json(
                [
                    'message' => "Erro ao tentar realizar transferência",
                    'errors' => $error->errors()
                ],
                JsonResponse::HTTP_UNPROCESSABLE_ENTITY
            );
        }
    }

    /**
     * Retorna em formato json as transações realizadas por ele.
     *
     * @return JsonResponse
     * @author Antonio Martins
     */
    public function retornarTransacoesPorUsuario(): JsonResponse
    {
        try {
            $idUsuarioLogado = Auth::user()->id;
            $dados = $this->model->where('id_usuario_pagador', '=', $idUsuarioLogado)->get();

            return response()->json($dados);
        } catch (Exception $e) {
            return response()->json(
                ['error' => 'Erro interno ao retornar transações do usuário'],
                JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
