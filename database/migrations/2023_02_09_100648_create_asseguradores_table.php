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
        Schema::create('asseguradores', function (Blueprint $table) {
            $table->string('CIF')->primary();
            $table->string('nom');
            $table->string('adreca');
            $table->integer('preu_per_cursa');
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
        Schema::dropIfExists('asseguradores');
    }
};
