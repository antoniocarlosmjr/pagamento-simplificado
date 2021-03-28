<?php

namespace Database\Factories;

use App\Models\Carteira;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Class CarteiraFactory
 * @package Database\Factories
 * @author Antonio Martins
 */
class CarteiraFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Carteira::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id_usuario' => User::factory(),
            'saldo' => $this->faker->randomFloat(2, 50, 150),
        ];
    }
}
