<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChangeLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('change_logs', function (Blueprint $table) {
            $table->id()->unsigned();
            $table->string('model_name', 255);
            $table->unsignedBigInteger('record_id')->nullable(false);;
            $table->unsignedBigInteger('user_id')->nullable(false);;
            $table->text('old_values')->nullable();
            $table->text('new_values')->nullable();
            $table->string('operation');
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
        Schema::dropIfExists('change_logs');
    }
}
