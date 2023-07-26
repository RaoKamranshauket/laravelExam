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
            $table->unsignedBigInteger('section_id')->after('exam_result_id');
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
            $table->dropColumn('section_id');
        });
    }
};
