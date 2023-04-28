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
        Schema::create('patrocinis', function (Blueprint $table) {
            $table->unsignedBigInteger('id_cursa');
            $table->string('cif_sponsor');
            $table->primary(['id_cursa', 'cif_sponsor']);
            $table->timestamps();
        });

        Schema::table('patrocinis', function (Blueprint $table) {
            $table->foreign('id_cursa')->references('id')->on('curses');
            $table->foreign('cif_sponsor')->references('CIF')->on('sponsors');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('patrocinis');
    }
};
