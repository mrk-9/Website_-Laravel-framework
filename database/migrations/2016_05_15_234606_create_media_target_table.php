<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMediaTargetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('media_target', function(Blueprint $table)
        {
            $table->integer('target_id')->unsigned();
            $table->integer('media_id')->unsigned();

            $table->foreign('target_id')
                ->references('id')
                ->on('target')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');

            $table->foreign('media_id')
                ->references('id')
                ->on('media')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('media_target', function(Blueprint $table) {
            $table->dropForeign('media_target_media_id_foreign');
        });

        Schema::drop('media_target');
    }
}
