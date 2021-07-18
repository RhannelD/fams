<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScholarshipPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scholarship_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable();
            $table->foreignId('scholarship_id');
            $table->string('title')->nullable();
            $table->mediumText('post');
            $table->boolean('promote')->default(0);
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('scholarship_id')->references('id')->on('scholarships');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('scholarship_posts', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['scholarship_id']);
            $table->dropColumn(['scholarship_id', 'user_id']);
        });
        
        Schema::dropIfExists('scholarship_posts');
    }
}
