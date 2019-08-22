<?php

use App\Models\VerifyUser;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class VerifyUsers extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('verify_users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('user_id');
            $table->string('token');
            $table->integer('scope')->default(VerifyUser::SCOPE_VERIFY_EMAIL);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('verify_users');
    }
}
