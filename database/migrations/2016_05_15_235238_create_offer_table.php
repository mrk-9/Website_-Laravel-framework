<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOfferTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offer', function(Blueprint $table)
        {
            $table->increments('id');
            $table->timestamps();
            $table->softDeletes();
            $table->string('publication_status')->default('publication_pending');
            $table->string('order')->nullable();
            $table->double('amount');

            $table->integer('user_id')->unsigned();
            $table->integer('ad_placement_id')->unsigned();
            $table->integer('admin_id')->unsigned()->nullable();
            $table->integer('ad_network_user_id')->unsigned()->nullable();

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

            $table->foreign('ad_network_user_id')
                ->references('id')
                ->on('ad_network_user')
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
        Schema::drop('offer');
    }

}
