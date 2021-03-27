<?php

namespace App\Models;

use App\Constantes\TiposUsuariosConstante;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * Class User
 * @package App\Models
 * @author Antonio Martins
 */
class User extends Authenticatable implements JWTSubject
{
    use Notifiable;
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'nome',
        'email',
        'cpf_cnpj',
        'password',
        'tipo'
    ];
    protected $table = "users";
    protected $hidden = ['password'];

    /**
     * Retorna um array de regras dos campos utilizados para cadastrar um usuário.
     *
     * @return array
     * @author Antonio Martins
     *
     */
    public function regras(): array
    {
        return [
            'nome' => 'required',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore('id')
            ],
            'cpf_cnpj' => [
                'required',
                'cpf_ou_cnpj',
                Rule::unique('users', 'cpf_cnpj')->ignore('id')
            ],
            'password' => 'required|min:8|max:20',
            'tipo' => [
                'required',
                Rule::in(TiposUsuariosConstante::USUARIO_COMUM, TiposUsuariosConstante::USUARIO_LOJISTA)
            ],
        ];
    }

    /**
     * Retorna as possíveis mensagens dos tipos de erros de validação da requisição.
     *
     * @return string[]
     * @author Antonio Martins
     *
     */
    public function mensagens(): array
    {
        return [
            'nome.required' => 'Nome do usuário é obrigatório.',
            'email.required' => 'Email é obrigatório.',
            'tipo.required' => 'Tipo do usuário é obrigatório.',
            'cpf_cnpj.required' => 'CPF/CNPJ deve é obrigatório.',
            'password.required' => 'Senha é obrigatória.',
            'email.unique' => 'Já existe um usuário com este email.',
            'cpf_cnpj.unique' => 'Já existe um usuário com este CPF/CNPJ.',
            'email.email' => 'Email informado não é válido.',
            'tipo.in' => "Tipo de usuário deve ser 'comum' ou 'logista'.",
            'cpf_cnpj.in' => 'CPF/CNPJ informado não é valido.',
            'password.min' => 'A senha deve ter no mínimo 8 e no máximo 20 caracteres.',
            'password.max' => 'A senha deve ter no mínimo 8 e no máximo 20 caracteres.',
        ];
    }

    /**
     * Retorna o identificaodor do usuário do JWT.
     *
     * @return mixed
     * @author Antonio Martins
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Returna um array de valores contendo qualquer declaração personalizada do JWT.
     *
     * @return array
     * @author Antonio Martins
     *
     */
    public function getJWTCustomClaims(): array
    {
        return [];
    }

    /**
     * Retorna se o usuário é do tipo lojista.
     *
     * @return bool
     * @author Antonio Martins
     */
    public function usuarioLojista(): bool
    {
        return $this->getAttribute('tipo') == TiposUsuariosConstante::USUARIO_LOJISTA;
    }

    /**
     * Retorna a carteira de um determinado usuário.
     *
     * @return Carteira
     * @author Antonio Martins
     *
     */
    public function getCarteira(): Carteira
    {
        return Carteira::where('id_usuario', '=', $this->id)->first();
    }
}
