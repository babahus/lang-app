<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExerciseIdToAccountsExercises extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('accounts_exercises', function (Blueprint $table) {
            $table->unsignedBigInteger('exercise_id');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('accounts_exercises', function (Blueprint $table) {
            $table->dropColumn('exercise_id');
        });
    }
}
