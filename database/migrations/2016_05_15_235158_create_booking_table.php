<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookingTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booking', function(Blueprint $table)
        {
            $table->increments('id');
            $table->timestamps();
            $table->softDeletes();

            $table->string('publication_status')->default('publication_pending');
            $table->string('order')->nullable();

            $table->integer('admin_id')->unsigned()->nullable();
            $table->integer('user_id')->unsigned();
            $table->integer('ad_placement_id')->unsigned();

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

        Schema::table('ad_placement', function(Blueprint $table) {
            $table->integer('lock_booking_id')->unsigned()->nullable();

            $table->foreign('lock_booking_id')
                ->references('id')
                ->on('booking')
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
        Schema::table('ad_placement', function(Blueprint $table) {
            $table->dropForeign('ad_placement_lock_booking_id_foreign');
            $table->dropColumn('lock_booking_id');
        });

        Schema::drop('booking');
    }

}
