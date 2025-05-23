<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserOtpsTable extends Migration
{
    /**
     * Run the migrations to user_otps.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_otps', function (Blueprint $table) {
            $table->id();
            $table->string('phone');
            $table->string('otp');
            $table->timestamp('expires_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations to user_otps.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_otps');
    }
}
