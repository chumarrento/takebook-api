<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSwclientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('swclients', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->longText('endpoint');
			$table->string('expiration_time')->nullable();
			$table->longText('key_p256dh');
			$table->longText('key_auth');
			$table->bigInteger('user_id')->nullable();
			$table->longText('user_token')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('swclients');
    }
}
