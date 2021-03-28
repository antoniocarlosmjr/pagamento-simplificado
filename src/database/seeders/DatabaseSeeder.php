<?php

namespace Database\Seeders;

use App\Models\Carteira;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Class DatabaseSeeder
 * @package Database\Seeders
 * @author Antonio Martins
 */
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $usuarios = User::factory(10)->create();
        $usuarios->each(
            fn (User $user) => Carteira::factory(
                [
                    'id_usuario' => $user->id,
                    'saldo' => 100
                ]
            )->create()
        );
    }
}
