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
        Schema::table('questions', function (Blueprint $table) {
            //
            $table->unsignedBigInteger('exam_id')->after('id');
            $table->integer('section_id')->after('exam_id');
            $table->tinyInteger('question_type')->default(1)->after('section_id'); //  1 Means option 
            $table->string('question_input')->nullable()->after('question_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('questions', function (Blueprint $table) {
            //
            $table->dropColumn('exam_id');
            $table->dropColumn('section_id');
            $table->dropColumn('question_type');
            $table->dropColumn('question_input');
        });
    }
};
