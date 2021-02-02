<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOverworksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('overworks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->datetime('scheduled_end_time');
            $table->boolean('is_next_day')->default(false);
            $table->string('business_description')->nullable();
            $table->integer('instructor_id');
            $table->string('apply_overtime_status')->default('0');
            $table->bigInteger('attendance_id')->unsigned();
            $table->foreign('attendance_id')->references('id')->on('attendances');
            $table->date('attendance_day');
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
        Schema::dropIfExists('overworks');
    }
}