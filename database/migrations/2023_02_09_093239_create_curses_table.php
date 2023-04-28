<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('curses', function (Blueprint $table) {
            $table->id();
            $table->string('descripcio');
            $table->string('desnivell');
            $table->string('img_mapa');
            $table->integer('max_participants');
            $table->string('longitud');
            $table->date('data_cursa');
            $table->time('hora_cursa');
            $table->string('punt_sortida');
            $table->string('cartell_promocio');
            $table->double('cost_patrocini');
            $table->boolean('actiu');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('curses');
    }
};
