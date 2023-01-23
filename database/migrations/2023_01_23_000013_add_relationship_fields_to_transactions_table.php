<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToTransactionsTable extends Migration
{
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->unsignedBigInteger('bank_id')->nullable();
            $table->foreign('bank_id', 'bank_fk_7908114')->references('id')->on('banks');
            $table->unsignedBigInteger('entry_user_id')->nullable();
            $table->foreign('entry_user_id', 'entry_user_fk_7908116')->references('id')->on('users');
            $table->unsignedBigInteger('approver_id')->nullable();
            $table->foreign('approver_id', 'approver_fk_7908480')->references('id')->on('users');
        });
    }
}
