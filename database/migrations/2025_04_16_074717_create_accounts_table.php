<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
Schema::create('accounts', function (Blueprint $table) {
    $table->id();
    $table->string('account_number')->unique();
    $table->foreignId('user_id')->constrained();
    $table->string('first_name');
    $table->string('last_name');
    $table->date('date_of_birth')->nullable();
    $table->text('address')->nullable();
    $table->decimal('balance', 15, 2)->default(10000);
    $table->string('currency')->default('USD');
    $table->boolean('is_active')->default(true);
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
        Schema::dropIfExists('accounts');
    }
}
