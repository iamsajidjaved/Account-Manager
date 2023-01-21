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
            $table->string('customer_name')->unique();
            $table->float('amount', 15, 4);
            $table->string('reference');
            $table->string('status');
            $table->string('beneficiary_bank')->nullable();
            $table->string('withdraw_purpose')->nullable();
            $table->datetime('entry_datetime')->nullable();
            $table->string('transaction_type');
            $table->string('deposit_no')->nullable();
            $table->string('approver_remarks')->nullable();
            $table->datetime('approve_datetime')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
