<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;


class UpdateTotalWorkTimeToSeconds extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('UPDATE attendances SET total_work_time = total_work_time * 60 WHERE total_work_time IS NOT NULL');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('UPDATE attendances SET total_work_time = total_work_time / 60 WHERE total_work_time IS NOT NULL');
    }
}
