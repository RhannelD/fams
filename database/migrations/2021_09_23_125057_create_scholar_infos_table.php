<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScholarInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scholar_infos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('school_id');
            $table->foreignId('course_id');
            $table->integer('year');
            $table->string('mother_name', 1000);
            $table->date('mother_birthday');
            $table->string('mother_address');
            $table->string('mother_occupation');
            $table->string('mother_educational_attainment');
            $table->boolean('mother_living');
            $table->string('father_name', 1000);
            $table->date('father_birthday');
            $table->string('father_address');
            $table->string('father_occupation');
            $table->string('father_educational_attainment');
            $table->boolean('father_living');
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('school_id')->references('id')->on('scholar_schools')->onDelete('cascade');
            $table->foreign('course_id')->references('id')->on('scholar_courses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('scholar_infos', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['school_id']);
            $table->dropForeign(['course_id']);
            $table->dropColumn(['user_id', 'school_id', 'course_id']);
        });
        
        Schema::dropIfExists('scholar_infos');
    }
}
