<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourseStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_courses_students', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id')->nullable(false);
            $table->unsignedBigInteger('course_id')->nullable(false);
            $table->timestamp('added_at')->nullable(false);
            $table->timestamp('purchased_at')->nullable();
            $table->timestamps();
            $table->foreign('student_id')->references('id')->on('users');
            $table->foreign('course_id')->references('id')->on('accounts_courses');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('account_courses_students');
    }
}
