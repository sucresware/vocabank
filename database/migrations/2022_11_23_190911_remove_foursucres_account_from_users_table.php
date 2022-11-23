<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveFoursucresAccountFromUsersTable extends Migration
{

    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('fourSucres_account');
        });
    }

    public function down()
    {
        echo 'This migration is irreversible' . PHP_EOL;
    }
}
