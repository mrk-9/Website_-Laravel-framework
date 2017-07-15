<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSelectionTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('selection', function(Blueprint $table) {
            $table->increments('id');
            $table->timestamps();

            $table->integer('ad_placement_id')->unsigned();
            $table->integer('user_id')->unsigned();

            $table->foreign('ad_placement_id')
                ->references('id')
                ->on('ad_placement')
                ->onUpdate('CASCADE')
                ->onDelete('SET NULL');

            $table->foreign('user_id')
                ->references('id')
                ->on('user')
                ->onUpdate('CASCADE')
                ->onDelete('SET NULL');

            $table->unique(['user_id', 'ad_placement_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('selection');
    }

}
