<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlayersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('players', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('rpg_id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('credential');
            $table->unsignedInteger('gold');
            $table->unsignedInteger('cash');
            $table->text('detail');
            $table->timestamps();

            $table->foreign('rpg_id')->references('id')->on('rpgs')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('players');
    }
}
