<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserSecuritiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_securities', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('model_type')->nullable();
            $table->bigInteger('model_id')->nullable();
            $table->string('security_pin')->nullable();
            $table->string('mnemonic_entropy')->nullable();
            $table->string('2fa_key')->nullable();
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
        Schema::dropIfExists('user_securities');
    }
}
