<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('games', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 100)->unique();
            $table->string('password')->nullable();
            $table->enum('status', ['accepting', 'running', 'finished', 'cancelled']);
            $table->integer('period')->nullable();
            $table->enum('phase', ['pre', 'inbound', 'postInbound', 'production', 'postProduction', 'outbound', 'postOutbound', 'cleanup', 'postCleanup'])->nullable();
            $table->bigInteger('master_id')->unsigned();
            $table->bigInteger('rules_id')->unsigned();

            $table->foreign('rules_id')->references('id')->on('rule_sets');
            $table->foreign('master_id')->references('id')->on('users');
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
        Schema::dropIfExists('games');
    }
}
