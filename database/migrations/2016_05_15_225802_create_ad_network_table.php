<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdNetworkTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ad_network', function(Blueprint $table)
        {
            $table->increments('id');
            $table->timestamps();
            $table->softDeletes();

            $table->string('name');
            $table->string('slug')->unique();
            $table->string('corporate_name');
            $table->string('company_type');
            $table->string('address');
            $table->string('address2')->default('');
            $table->string('zipcode');
            $table->string('city');
            $table->string('phone');
            $table->string('email');
            $table->integer('supports');
            $table->string('status');
            $table->decimal('deposit_percent');
            $table->integer('ad_network_user_id')
                ->unsigned()
                ->nullable()
                ->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('ad_network');
    }

}
