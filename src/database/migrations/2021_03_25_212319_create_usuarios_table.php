<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateUsuariosTable
 * @author Antonio Martins
 */
class CreateUsuariosTable extends Migration
{
    /**
     * Executa as migrations.
     *
     * @return void
     * @author Antonio Martins
     *
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 150);
            $table->string('email', 100)->unique();
            $table->string('password');
            $table->string('cpf_cnpj', 14)->unique();
            $table->enum('tipo', ['comum', 'lojista']);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverte as migrations.
     *
     * @return void
     * @author Antonio Martins
     *
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
