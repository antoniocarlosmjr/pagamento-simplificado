<?php

namespace Database\Factories;

use App\Constantes\TiposUsuariosConstante;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Provider\pt_BR\Person;
use Faker\Provider\pt_BR\Company;
use Illuminate\Support\Arr;

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
        $this->faker->addProvider(new Person($this->faker));
        $this->faker->addProvider(new Company($this->faker));

        $tipoUsuario = Arr::random(TiposUsuariosConstante::getArraySomenteIds());
        $documentoUsuario = $tipoUsuario == TiposUsuariosConstante::USUARIO_COMUM
            ? $this->faker->cpf(false)
            : $this->faker->cnpj(false);

        $nomeUsuario = $tipoUsuario == TiposUsuariosConstante::USUARIO_COMUM
            ? "{$this->faker->firstName()} {$this->faker->lastName()}"
            : $this->faker->company();

        return [
            'nome' => $nomeUsuario,
            'email' => $this->faker->unique()->safeEmail,
            'password' => bcrypt('12345678'),
            'cpf_cnpj' => $documentoUsuario,
            'tipo' => $tipoUsuario
        ];
    }
}
