<?php

namespace App\Models;

use App\Exceptions\CarteiraException;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;

/**
 * Class Carteira
 * @package App\Models
 * @author Antonio Martins
 */
class Carteira extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_usuario',
        'saldo'
    ];

    /**
     * Realiza a criação de uma carteira de um usuário.
     *
     * @param User $usuarioModel
     * @return Carteira
     * @throws CarteiraException
     * @author Antonio Martins
     */
    public function criarCarteiraUsuario(User $usuarioModel): Carteira
    {
        try {
            $form['id_usuario'] =  $usuarioModel->id;
            $form['saldo'] =  0;

            return $this->create($form);
        } catch (Exception $e) {
            throw new CarteiraException(
                'Erro ao tentar cadastrar a reserva',
                JsonResponse::HTTP_UNPROCESSABLE_ENTITY
            );
        }
    }
}
