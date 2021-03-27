<?php

namespace App\Http\Controllers;

use App\Models\Carteira;
use App\Models\Usuario;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

/**
 * Class UsuarioController
 * @package App\Http\Controllers
 * @author Antonio Martins
 */
class UsuarioController extends GenericoController
{
    protected $model;

    /**
     * UsuarioController constructor.
     * @param Usuario $usuarioModel
     */
    public function __construct(Usuario $usuarioModel)
    {
        $this->model = $usuarioModel;
    }

    /**
     * Realiza o cadastro de um novo usuário e logo após cadastro a sua carteira.
     *
     * @param Request $request
     * @return JsonResponse
     * @author Antonio Martins
     */
    public function cadastrar(Request $request)
    {
        try {
            DB::beginTransaction();

            $request->validate($this->model->regras(), $this->model->mensagens());
            $dataForm = $request->all();

            $dataForm['senha'] = bcrypt($dataForm['senha']);
            $usuarioModelObj = $this->model->create($dataForm);

            $carteiraModelObj = new Carteira();
            $carteiraModelObj->criarCarteiraUsuario($usuarioModelObj);

            DB::commit();

            return response()->json(
                ['success' => 'Usuário cadastrado com sucesso!'],
                JsonResponse::HTTP_CREATED
            );
        } catch (Throwable $error){
            return response()->json(
                [
                    'message' => "Erro ao cadastrar usuário",
                    'errors' => $error->errors()
                ],
                JsonResponse::HTTP_UNPROCESSABLE_ENTITY
            );
        }

    }
}
