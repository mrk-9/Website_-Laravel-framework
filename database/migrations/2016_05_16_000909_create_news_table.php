<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('news', function(Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->softDeletes();

            $table->string('slug')->unique();
            $table->string('status');
            $table->string('picture');
            $table->string('title');
            $table->text('abstract');
            $table->longText('content');
            $table->timestamp('published_at');
            $table->integer('admin_id')->unsigned();
            $table->integer('news_category_id')->unsigned();

            $table->foreign('news_category_id')
                ->references('id')
                ->on('news_category')
                ->onUpdate('CASCADE')
                ->onDelete('SET NULL');

            $table->foreign('admin_id')
                ->references('id')
                ->on('admin')
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
        Schema::drop('news');
    }

}
