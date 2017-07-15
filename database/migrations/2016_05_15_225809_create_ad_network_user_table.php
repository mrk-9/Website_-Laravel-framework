<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdNetworkUserTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ad_network_user', function(Blueprint $table)
        {
            $table->increments('id');
            $table->timestamps();
            $table->softDeletes();

            $table->string('name');
            $table->string('family_name');
            $table->string('title');
            $table->string('email')->unique();
            $table->string('phone');
            $table->string('password', 60)->nullable();
            $table->string('position')->nullable();
            $table->rememberToken();
            $table->integer('ad_network_id')->unsigned();

            $table->foreign('ad_network_id')
                ->references('id')
                ->on('ad_network')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');
        });

        Schema::table('ad_network', function(Blueprint $table)
        {
            $table->foreign('ad_network_user_id')
                ->references('id')
                ->on('ad_network_user')
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
        Schema::table('ad_network', function(Blueprint $table)
        {
            $table->dropForeign('ad_network_ad_network_user_id_foreign');
        });

        Schema::drop('ad_network_user');
    }

}
