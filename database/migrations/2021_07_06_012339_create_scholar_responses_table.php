<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScholarResponsesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scholar_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('requirement_id')->unique();
            $table->boolean('approval')->nullable();
            $table->timestamp('submit_at')->nullable();
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('requirement_id')->references('id')->on('scholarship_requirements');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('scholar_responses', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['requirement_id']);
            $table->dropColumn(['user_id', 'requirement_id']);
        });
        
        Schema::dropIfExists('scholar_responses');
    }
}
