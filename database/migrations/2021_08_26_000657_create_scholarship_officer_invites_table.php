<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScholarshipOfficerInvitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scholarship_officer_invites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scholarship_id');
            $table->string('email');
            $table->string('token');
            $table->string('code', 6)->nullable();
            $table->boolean('respond')->nullable();
            $table->timestamps();
            
            $table->foreign('scholarship_id')->references('id')->on('scholarships')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('scholarship_officer_invites', function (Blueprint $table) {
            $table->dropForeign(['scholarship_id']);
            $table->dropColumn(['scholarship_id']);
        });
        
        Schema::dropIfExists('scholarship_officer_invites');
    }
}
