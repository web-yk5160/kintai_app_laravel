<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('belong')->nullable();
            $table->boolean('admin_flg')->default(false);
            $table->boolean('superior_flg')->default(false);
            $table->time('designate_start_time')->nullable();
            $table->time('designate_end_time')->nullable();
            $table->time('basic_work_time')->nullable();
            $table->string('number')->nullable();
            $table->string('card_number')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('belong');
            $table->dropColumn('admin_flg');
            $table->dropColumn('superior_flg');
            $table->dropColumn('designate_start_time');
            $table->dropColumn('designate_end_time');
            $table->dropColumn('basic_work_time');
            $table->dropColumn('number');
            $table->dropColumn('card_number');
        });
    }
}