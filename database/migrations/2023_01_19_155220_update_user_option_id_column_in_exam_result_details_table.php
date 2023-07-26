<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('exam_result_details', function (Blueprint $table) {
            //
            $table->renameColumn('user_result_id','exam_result_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('exam_result_details', function (Blueprint $table) {
            //
            $table->renameColumn('exam_result_id','user_result_id');
        });
    }
};
