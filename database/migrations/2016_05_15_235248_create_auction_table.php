<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuctionTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auction', function(Blueprint $table)
        {
            $table->increments('id');
            $table->timestamps();
            $table->softDeletes();
            $table->string('order')->nullable();
            $table->decimal('amount');
            $table->string('publication_status')->default('publication_pending');
            $table->integer('user_id')->unsigned();
            $table->integer('ad_placement_id')->unsigned();
            $table->integer('admin_id')->unsigned()->nullable();

            $table->foreign('user_id')
                ->references('id')
                ->on('user')
                ->onUpdate('CASCADE')
                ->onDelete('SET NULL');

            $table->foreign('ad_placement_id')
                ->references('id')
                ->on('ad_placement')
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
        Schema::drop('auction');
    }

}
