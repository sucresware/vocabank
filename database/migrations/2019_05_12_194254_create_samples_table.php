<?php

use App\Models\Sample;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSamplesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('samples', function (Blueprint $table) {
            $table->uuid('id');
            $table->uuid('user_id');

            $table->string('name');
            $table->unsignedInteger('status')->default(Sample::STATUS_DRAFT);
            $table->unsignedInteger('duration')->default(0);

            $table->string('audio')->nullable();
            $table->string('waveform')->nullable();
            $table->string('thumbnail')->nullable();

            $table->text('description')->nullable();
            $table->json('settings')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('samples');
    }
}
