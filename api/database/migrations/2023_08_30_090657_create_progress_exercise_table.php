<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProgressExerciseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('progress_exercise', function (Blueprint $table) {
            $table->id()->unsigned();
            $table->unsignedBigInteger('accounts_exercise_id');
            $table->unsignedBigInteger('account_id');
            $table->boolean('solved')->default(false);
            $table->dateTime('solved_at')->nullable();
            $table->boolean('course_attachment')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('progress_exercise');
    }
}
