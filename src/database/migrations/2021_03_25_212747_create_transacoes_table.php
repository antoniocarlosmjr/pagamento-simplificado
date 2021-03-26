<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateTransacoesTable
 * @author Antonio Martins antonioc@construtordevendas.com.br
 */
class CreateTransacoesTable extends Migration
{
    /**
     * Executa as migrations.
     *
     * @return void
     * @author Antonio Martins antonioc@construtordevendas.com.br
     *
     */
    public function up()
    {
        Schema::create('transacoes', function (Blueprint $table) {
            $table->id();
            $table->decimal('valor', 6, 2, true);
            $table->bigInteger('id_usuario_pagador')->unsigned();
            $table->enum('situacao', ['finalizada', 'pendente', 'cancelada', 'nao-autorizada']);
            $table->foreign('id_usuario_pagador')
                ->references('id')
                ->on('usuarios')
                ->onUpdate('CASCADE')
                ->onDelete('NO ACTION');

            $table->bigInteger('id_usuario_beneficiario')->unsigned();
            $table->foreign('id_usuario_beneficiario')
                ->references('id')
                ->on('usuarios')
                ->onUpdate('CASCADE')
                ->onDelete('NO ACTION');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverte as migrations.
     *
     * @return void
     * @author Antonio Martins antonioc@construtordevendas.com.br
     *
     */
    public function down()
    {
        Schema::table('transacoes', function (Blueprint $table) {
            $table->dropConstrainedForeignId('id_usuario_pagador');
            $table->dropConstrainedForeignId('id_usuario_beneficiario');
        });
        Schema::dropIfExists('transacoes');
    }
}
