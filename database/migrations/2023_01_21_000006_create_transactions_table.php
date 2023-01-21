<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('transaction_type');
            $table->string('customer_name')->unique();
            $table->float('amount', 15, 4);
            $table->string('reference');
            $table->string('status');
            $table->datetime('entry_datetime')->nullable();
            $table->string('deposit_no')->nullable();
            $table->string('approver_remarks')->nullable();
            $table->datetime('approve_datetime')->nullable();
            $table->string('remarks')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
