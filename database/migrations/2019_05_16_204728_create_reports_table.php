<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('game_id')->unsigned(); 
            $table->bigInteger('role_id')->unsigned();
            $table->integer('period');
            $table->enum('phase', ['inbound', 'production', 'outbound', 'cleanup']);
            $table->json('report');
            $table->timestamps();

            $table->foreign('game_id')->references('id')->on('games');
            $table->foreign('role_id')->references('id')->on('roles');

            $table->unique(['game_id', 'role_id', 'period', 'phase']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reports');
    }
}
