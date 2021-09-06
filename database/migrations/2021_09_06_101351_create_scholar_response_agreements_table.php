<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScholarResponseAgreementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scholar_response_agreements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('response_id');
            $table->foreignId('agreement_id');
            $table->timestamps();
            
            $table->foreign('response_id')->references('id')->on('scholar_responses')->onDelete('cascade');
            $table->foreign('agreement_id')->references('id')->on('scholarship_requirement_agreements')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('scholar_response_agreements', function (Blueprint $table) {
            $table->dropForeign(['response_id']);
            $table->dropForeign(['agreement_id']);
            $table->dropColumn(['response_id', 'agreement_id']);
        });
        
        Schema::dropIfExists('scholar_response_agreements');
    }
}
