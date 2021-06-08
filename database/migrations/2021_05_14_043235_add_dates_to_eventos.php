<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDatesToEventos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Nuevas columnas a la tabla de eventos
        Schema::table('eventos', function (Blueprint $table) {
            // Ruta de la imagen asociada al evento
            $table->string('imagen', 400)->default('');
            $table->dateTime('dateInit')->nullable();
            $table->dateTime('dateEnd')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('eventos', function (Blueprint $table) {
            //
        });
    }
}
