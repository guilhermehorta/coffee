<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRuleSetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rule_sets', function (Blueprint $table) {
            $table->bigIncrements('id'); 
            $table->string('name', 100)->unique();
            $table->json('material'); //Dados de materias primas e materiais
            $table->json('map'); //Localização de entidades
            $table->json('vehicle'); //dados de veículos
            $table->json('game'); //Dados de jogo
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
        Schema::dropIfExists('rule_sets');
    }
}
