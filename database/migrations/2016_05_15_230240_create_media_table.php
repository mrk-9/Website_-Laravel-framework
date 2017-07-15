<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMediaTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('media', function(Blueprint $table)
        {
            $table->increments('id');
            $table->SoftDeletes();

            $table->string('name');
            $table->string('slug')->unique();
            $table->string('cover')->nullable();
            $table->string('technical_doc')->nullable();
            $table->text('datas')->nullable();

            $table->integer('ad_network_id')->unsigned()->nullable();
            $table->integer('frequency_id')->unsigned()->nullable();
            $table->integer('broadcasting_area_id')->unsigned()->nullable();
            $table->integer('category_id')->unsigned()->nullable();
            $table->integer('support_id')->unsigned()->nullable();
            $table->integer('theme_id')->unsigned()->nullable();

            $table->foreign('ad_network_id')
                ->references('id')
                ->on('ad_network')
                ->onUpdate('CASCADE')
                ->onDelete('SET NULL');

            $table->foreign('frequency_id')
                ->references('id')
                ->on('frequency')
                ->onUpdate('CASCADE')
                ->onDelete('SET NULL');

            $table->foreign('broadcasting_area_id')
                ->references('id')
                ->on('broadcasting_area')
                ->onUpdate('CASCADE')
                ->onDelete('SET NULL');

            $table->foreign('category_id')
                ->references('id')
                ->on('category')
                ->onUpdate('CASCADE')
                ->onDelete('SET NULL');

            $table->foreign('support_id')
                ->references('id')
                ->on('support')
                ->onUpdate('CASCADE')
                ->onDelete('SET NULL');

            $table->foreign('theme_id')
                ->references('id')
                ->on('theme')
                ->onUpdate('CASCADE')
                ->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('media');
    }

}
