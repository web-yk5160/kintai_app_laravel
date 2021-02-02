<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->datetime('start_time')->nullable();
            $table->datetime('end_time')->nullable();
            $table->date('attendance_day')->nullable();
            $table->string('note')->nullable();
            $table->boolean('is_next_day')->default(false);
            $table->integer('instructor_id')->nullable();
            $table->string('apply_status')->default('0');
            $table->datetime('previous_start_time')->nullable();
            $table->datetime('previous_end_time')->nullable();
            $table->date('approval_date')->nullable();
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
        Schema::dropIfExists('attendances');
    }
}