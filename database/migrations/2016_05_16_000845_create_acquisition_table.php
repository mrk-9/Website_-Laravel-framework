<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAcquisitionTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acquisition', function(Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->softDeletes();

            $table->decimal('price');
            $table->decimal('vat_rate');
            $table->text('brief')->nullable();
            $table->decimal('technical_support_price')->nullable();
            $table->integer('technical_support_id')->unsigned()->nullable();
            $table->integer('template_id')->unsigned()->nullable();
            $table->integer('ad_placement_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->integer('invoice_id')->unsigned()->nullable();
            $table->string('charge_status')->nullable();
            $table->string('transfer_status')->nullable();
            $table->string('charge_id')->unsigned()->nullable();

            $table->foreign('technical_support_id')
                ->references('id')
                ->on('technical_support')
                ->onUpdate('CASCADE')
                ->onDelete('SET NULL');

            $table->foreign('template_id')
                ->references('id')
                ->on('template')
                ->onUpdate('CASCADE')
                ->onDelete('SET NULL');

            $table->foreign('ad_placement_id')
                ->references('id')
                ->on('ad_placement')
                ->onUpdate('CASCADE')
                ->onDelete('SET NULL');

            $table->foreign('user_id')
                ->references('id')
                ->on('user')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');

            $table->foreign('invoice_id')
                ->references('id')
                ->on('invoice')
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
        Schema::drop('acquisition');
    }

}
