<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRelationshipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rpg_user', function (Blueprint $table) {
            $table->unsignedInteger('rpg_id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('credential');
            $table->unsignedInteger('gold');
            $table->unsignedInteger('cash');
            $table->text('detail');

            $table->foreign('rpg_id')->references('id')->on('rpgs')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::create('item_user', function (Blueprint $table) {
            $table->unsignedInteger('item_id');
            $table->unsignedInteger('user_id');
            $table->boolean('status');

            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::create('quest_user', function (Blueprint $table) {
            $table->unsignedInteger('quest_id');
            $table->unsignedInteger('user_id');

            $table->foreign('quest_id')->references('id')->on('quests')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::create('item_quest', function (Blueprint $table) {
            $table->unsignedInteger('item_id');
            $table->unsignedInteger('quest_id');

            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
            $table->foreign('quest_id')->references('id')->on('quests')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rpg_user');
        Schema::dropIfExists('item_user');
        Schema::dropIfExists('quest_user');
        Schema::dropIfExists('item_quest');
    }
}
