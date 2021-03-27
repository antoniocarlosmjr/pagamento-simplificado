<?php


namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;
use Throwable;

/**
 * Class GenericoController
 * @package App\Http\Controllers
 * @author Antonio Martins
 */
abstract class GenericoController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $model;

    /**
     * Retorna todos os dados de uma determinada tabela.
     *
     * @return JsonResponse
     * @author Antonio Martins
     */
    public function retornarTodos(): JsonResponse
    {
        try {
            $dados = $this->model->all();

            if ($dados->isEmpty()) {
                return response()->json(
                    ['error' => 'Nenhum dado encontrado!'],
                    JsonResponse::HTTP_NOT_FOUND);
            }

            return response()->json($dados);
        } catch (Exception $e) {
            return response()->json(
                ['error' => 'Erro interno'],
                JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Retorna um determinado dado da tabela buscado pelo seu id.
     *
     * @param int $id
     * @return JsonResponse
     * @author Antonio Martins
     */
    public function retornarPorId(int $id): JsonResponse
    {
        try {
            if (!$data = $this->model->find($id)) {
                return response()->json(
                    ['error' => 'Nenhum dado encontrado!'],
                    JsonResponse::HTTP_NOT_FOUND);
            }

            return response()->json($data, JsonResponse::HTTP_OK);
        } catch (Exception $e) {
            return response()->json(
                ['error' => 'Erro ao retornar por id'],
                JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Realiza a atualização de um dado na tabela
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     * @author Antonio Martins
     */
    public function atualizar(Request $request, int $id): JsonResponse
    {
        try {
            if (!$data = $this->model->find($id)) {
                return response()->json(
                    ['error' => 'Nenhum dado encontrado!'],
                    JsonResponse::HTTP_NOT_FOUND);
            }

            $request->validate($this->model->regras(), $this->model->mensagens());

            $dataForm = $request->all();
            $data->update($dataForm);

            return response()->json(
                ['success' => 'Atualizado com sucesso!'],
                JsonResponse::HTTP_OK);
        } catch (Throwable $error) {
            return response()->json(
                [
                    'error' => 'Erro ao tentar atualizar',
                    'errors' => $error->errors()
                ],
                JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Realiza a remoção de um determinado dado na tabela buscado pelo id.
     *
     * @param int $id
     * @return JsonResponse
     * @author Antonio Martins
     */
    public function deletar(int $id): JsonResponse
    {
        try {
            if ($data = $this->model->find($id)) {
                $data->delete();
                return response()->json(
                    ['success' => 'Deletado com sucesso!'],
                    JsonResponse::HTTP_OK);
            }
        } catch (Exception $e) {
            return response()->json(
                ['error' => 'Erro ao tentar deletar'],
                JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

}
