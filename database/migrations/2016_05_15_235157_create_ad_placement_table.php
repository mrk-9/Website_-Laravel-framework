<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdPlacementTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ad_placement', function(Blueprint $table)
        {
            $table->increments('id');
            $table->softDeletes();
            $table->timestamps();

            $table->timestamp('starting_at');
            $table->timestamp('ending_at');
            $table->timestamp('technical_deadline')->nullable();
            $table->timestamp('lock_ending_at')->nullable();
            $table->timestamp('broadcasting_date')->nullable();
            $table->timestamp('locking_up')->nullable();

            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->decimal('price');
            $table->string('type');
            $table->decimal('minimum_price')->nullable();
            $table->integer('edition')->unsigned()->nullable();
            $table->string('position')->nullable();
            $table->text('deletion_cause')->nullable();

            $table->integer('media_id')->unsigned()->nullable();
            $table->integer('format_id')->unsigned()->nullable();

            $table->foreign('media_id')
                ->references('id')
                ->on('media')
                ->onUpdate('CASCADE')
                ->onDelete('SET NULL');

            $table->foreign('format_id')
                ->references('id')
                ->on('format')
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
        Schema::drop('ad_placement');
    }

}
