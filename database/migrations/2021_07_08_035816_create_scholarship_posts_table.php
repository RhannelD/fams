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
            $table->foreignId('scholarship_id');
            $table->mediumText('post');
            $table->boolean('promote')->default(0);
            $table->timestamps();
            
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
            $table->dropForeign(['scholarship_id']);
            $table->dropColumn(['scholarship_id']);
        });
        
        Schema::dropIfExists('scholarship_posts');
    }
}
