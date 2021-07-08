<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScholarResponseCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scholar_response_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('response_id');
            $table->foreignId('user_id');
            $table->text('comment');
            $table->timestamps();
            
            $table->foreign('response_id')->references('id')->on('scholar_responses');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('scholar_response_comments', function (Blueprint $table) {
            $table->dropForeign(['response_id']);
            $table->dropForeign(['user_id']);
            $table->dropColumn(['user_id', 'response_id']);
        });
        
        Schema::dropIfExists('scholar_response_comments');
    }
}
