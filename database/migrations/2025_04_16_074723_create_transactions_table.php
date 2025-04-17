<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

Schema::create('transactions', function (Blueprint $table) {
    $table->id();
    $table->foreignId('account_id')->constrained();
    $table->decimal('amount', 15, 2);
    $table->string('type'); // deposit, withdrawal, transfer
    $table->string('currency');
    $table->decimal('conversion_rate', 10, 4)->nullable();
    $table->text('description')->nullable();
    $table->foreignId('related_account_id')->nullable()->constrained('accounts');
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
        Schema::dropIfExists('transactions');
    }
}
