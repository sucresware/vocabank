<?php

use App\Models\User;
use App\Models\VerifyUser;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TransformUuidsToIds extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('likeable_likes', function (Blueprint $table) {
            $table->dropUnique('likeable_likes_unique');
        });

        Schema::table('likeable_like_counters', function (Blueprint $table) {
            $table->dropUnique('likeable_counts');
        });

        $this->createIdentifierColumn('users');
        $this->createIdentifierColumn('samples');

        $this->createChildrenIdentifierColumn('likeable_like_counters', 'likable');
        $this->createChildrenIdentifierColumn('likeable_likes', 'likable');
        $this->createChildrenIdentifierColumn('likeable_likes', 'user');
        $this->createChildrenIdentifierColumn('sample_tag', 'sample');
        $this->createChildrenIdentifierColumn('verify_users', 'user');
        $this->createChildrenIdentifierColumn('samples', 'user');
        $this->createChildrenIdentifierColumn('activity_log', 'subject');
        $this->createChildrenIdentifierColumn('activity_log', 'causer');

        $users = DB::table('users')->get();

        foreach ($users as $user) {
            DB::table('likeable_likes')
                ->where('user_id', $user->id)
                ->update(['user_identifier' => $user->identifier]);

            DB::table('verify_users')
                ->where('user_id', $user->id)
                ->update(['user_identifier' => $user->identifier]);

            DB::table('samples')
                ->where('user_id', $user->id)
                ->update(['user_identifier' => $user->identifier]);

            DB::table('activity_log')
                ->where('causer_id', $user->id)
                ->update(['causer_identifier' => $user->identifier]);
        }

        $samples = DB::table('samples')->get();

        foreach ($samples as $sample) {
            DB::table('likeable_likes')
                ->where('likable_id', $sample->id)
                ->update(['likable_identifier' => $sample->identifier]);

            DB::table('likeable_like_counters')
                ->where('likable_id', $sample->id)
                ->update(['likable_identifier' => $sample->identifier]);

            DB::table('sample_tag')
                ->where('sample_id', $sample->id)
                ->update(['sample_identifier' => $sample->identifier]);

            DB::table('activity_log')
                ->where('subject_id', $sample->id)
                ->update(['subject_identifier' => $sample->identifier]);
        }

        $this->swapChildrenIdentifierColumn('likeable_like_counters', 'likable');
        $this->swapChildrenIdentifierColumn('likeable_likes', 'likable');
        $this->swapChildrenIdentifierColumn('likeable_likes', 'user');
        $this->swapChildrenIdentifierColumn('sample_tag', 'sample');
        $this->swapChildrenIdentifierColumn('verify_users', 'user');
        $this->swapChildrenIdentifierColumn('samples', 'user');
        $this->swapChildrenIdentifierColumn('activity_log', 'subject');
        $this->swapChildrenIdentifierColumn('activity_log', 'causer');

        $this->swapIdentifierColumn('users');

        Schema::table('samples', function (Blueprint $table) {
            $table->renameColumn('id', 'uuid')->nullable();
            $table->renameColumn('identifier', 'id');
        });

        Schema::table('likeable_likes', function (Blueprint $table) {
            $table->unique(['likable_id', 'likable_type', 'user_id'], 'likeable_likes_unique');
        });

        Schema::table('likeable_like_counters', function (Blueprint $table) {
            $table->unique(['likable_id', 'likable_type'], 'likeable_counts');
        });
    }

    private function createIdentifierColumn($table)
    {
        Schema::table($table, function (Blueprint $table) {
            $table->bigIncrements('identifier')->first();
        });
    }

    private function swapIdentifierColumn($table)
    {
        Schema::table($table, function (Blueprint $table) {
            $table->dropColumn('id');
            $table->renameColumn('identifier', 'id');
        });
    }

    private function createChildrenIdentifierColumn($table, $model)
    {
        Schema::table($table, function (Blueprint $table) use ($model) {
            $table->bigInteger($model . '_identifier')->after($model . '_id');
        });
    }

    private function swapChildrenIdentifierColumn($table, $model)
    {
        Schema::table($table, function (Blueprint $table) use ($model) {
            $table->dropColumn($model . '_id');
            $table->renameColumn($model . '_identifier', $model . '_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        /**
         * This migration is not reversable.
         */
    }
}
