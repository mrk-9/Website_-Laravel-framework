<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBuyerTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buyer', function(Blueprint $table)
        {
            $table->increments('id');
            $table->timestamps();
            $table->softDeletes();

            $table->string('type');
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('company_type');
            $table->string('address');
            $table->string('zipcode');
            $table->string('city');
            $table->string('phone');
            $table->string('email');
            $table->string('status')->default('pending');
            $table->string('activity')->nullable();
            $table->text('customers')->nullable();
            $table->string('stripe_id')->nullable();
            $table->integer('user_id')
                ->unsigned()
                ->nullable();

            $table->foreign('user_id')
                ->references('id')
                ->on('user')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');
        });

        Schema::table('user', function(Blueprint $table)
        {
            $table->foreign('buyer_id')
                ->references('id')
                ->on('buyer')
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
        Schema::table('user', function(Blueprint $table)
        {
            $table->dropForeign('user_buyer_id_foreign');
        });

        Schema::drop('buyer');
    }

}
