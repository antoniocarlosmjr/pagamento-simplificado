<?php

namespace Database\Factories;

use App\Constantes\TiposUsuariosConstante;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Provider\pt_BR\Person;
use Faker\Provider\pt_BR\Company;

/**
 * Class UserFactory
 * @package Database\Factories
 * @author Antonio Martins
 */
class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $fakeBrPerson = new Person($this->faker);
        $fakeBrCompany = new Company($this->faker);

        $tipoUsuario = $this->faker->randomElement(TiposUsuariosConstante::getArrayCombo());
        $documentoUsuario = $tipoUsuario['id'] == TiposUsuariosConstante::USUARIO_COMUM
            ? $fakeBrPerson->cpf(false)
            : $fakeBrCompany->cnpj(false);
        $nomeUsuario = $tipoUsuario['id'] == TiposUsuariosConstante::USUARIO_COMUM
            ? "{$fakeBrPerson->firstName()} {$fakeBrPerson->lastName()}"
            : $fakeBrCompany->company();

        return [
            'nome' => $nomeUsuario,
            'email' => $this->faker->unique()->safeEmail,
            'password' => bcrypt('12345678'),
            'cpf_cnpj' => $documentoUsuario,
            'tipo' => $tipoUsuario['id']
        ];
    }
}
