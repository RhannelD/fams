<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScholarshipScholarInvitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scholarship_scholar_invites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id');
            $table->string('email');
            $table->boolean('sent')->nullable()->default(null);
            $table->year('acad_year');
            $table->tinyInteger('acad_sem');
            $table->boolean('respond')->nullable();
            $table->timestamps();
            
            $table->foreign('category_id')->references('id')->on('scholarship_categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('scholarship_scholar_invites', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn(['category_id']);
        });
        
        Schema::dropIfExists('scholarship_scholar_invites');
    }
}
