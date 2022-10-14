<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRastreioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rastreio', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->float('estoque');
            $table->text('foto');
            $table->string('codigo');
            $table->float('preco');
            $table->date('dataVerificada');
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
        Schema::dropIfExists('rastreio');
    }
}
