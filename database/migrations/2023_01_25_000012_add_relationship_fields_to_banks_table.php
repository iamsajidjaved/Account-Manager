<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToBanksTable extends Migration
{
    public function up()
    {
        Schema::table('banks', function (Blueprint $table) {
            $table->unsignedBigInteger('country_id')->nullable();
            $table->foreign('country_id', 'country_fk_7908044')->references('id')->on('countries');
            $table->unsignedBigInteger('group_id')->nullable();
            $table->foreign('group_id', 'group_fk_7908430')->references('id')->on('groups');
        });
    }
}
