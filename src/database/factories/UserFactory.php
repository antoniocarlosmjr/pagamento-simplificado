<?php

namespace Database\Factories;

use App\Constantes\TiposUsuariosConstante;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Provider\pt_BR\Person;

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
        $fakerBr = new Person($this->faker);


        return [
            'nome' => "{$this->faker->firstName} {$this->faker->lastName}",
            'email' => $this->faker->unique()->safeEmail,
            'password' => $this->faker->password(),
            'cpf_cnpj' => $fakerBr->cpf(false),
            'tipo' => $this->faker->randomElement(
                [
                    TiposUsuariosConstante::USUARIO_COMUM,
                    TiposUsuariosConstante::USUARIO_LOJISTA
                ]
            )
        ];
    }
}
