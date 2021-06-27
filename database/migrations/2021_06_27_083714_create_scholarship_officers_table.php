<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScholarshipOfficersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scholarship_officers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('scholarship_id');
            $table->foreignId('position_id');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('scholarship_id')->references('id')->on('scholarships');
            $table->foreign('position_id')->references('id')->on('officer_positions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('scholarship_officers', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['scholarship_id']);
            $table->dropForeign(['position_id']);
            $table->dropColumn(['user_id','scholarship_id','position_id']);
        });
        
        Schema::dropIfExists('scholarship_officers');
    }
}
