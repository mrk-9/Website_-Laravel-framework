<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoiceTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice', function(Blueprint $table) {
            $table->increments('id');
            $table->softDeletes();
            $table->timestamps();

            $table->string('name')->unique();
            $table->string('buyer_name');
            $table->string('buyer_address');
            $table->string('buyer_zipcode');
            $table->string('buyer_city');
            $table->decimal('amount');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('invoice');
    }

}
