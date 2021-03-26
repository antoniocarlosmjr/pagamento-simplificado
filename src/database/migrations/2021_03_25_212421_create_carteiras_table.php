<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateCarteirasTable
 * @author Antonio Martins
 */
class CreateCarteirasTable extends Migration
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
        Schema::create('carteiras', function (Blueprint $table) {
            $table->id();
            $table->decimal('saldo', 6, 2, true);
            $table->bigInteger('id_usuario')->unsigned();
            $table->foreign('id_usuario')
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
     * @author Antonio Martins
     *
     */
    public function down()
    {
        Schema::table('carteiras', function (Blueprint $table) {
            $table->dropConstrainedForeignId('id_usuario');
        });
        Schema::dropIfExists('carteiras');
    }
}
