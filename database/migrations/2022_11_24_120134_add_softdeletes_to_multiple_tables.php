<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSoftdeletesToMultipleTables extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('samples', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    public function down()
    {
        echo 'This migration is irreversible' . PHP_EOL;
    }
}
