<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScholarshipPostLinkRequirementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scholarship_post_link_requirements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id');
            $table->foreignId('requirement_id');
            $table->timestamps();
            
            $table->foreign('post_id')->references('id')->on('scholarship_posts')->onDelete('cascade');
            $table->foreign('requirement_id')->references('id')->on('scholarship_requirements')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('scholarship_post_link_requirements', function (Blueprint $table) {
            $table->dropForeign(['post_id']);
            $table->dropForeign(['requirement_id']);
            $table->dropColumn(['requirement_id', 'post_id']);
        });
        
        Schema::dropIfExists('scholarship_post_link_requirements');
    }
}
