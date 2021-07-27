<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScholarResponseOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scholar_response_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('response_id');
            $table->foreignId('item_id');
            $table->foreignId('option_id');
            $table->timestamps();
            
            $table->foreign('response_id')->references('id')->on('scholar_responses')->onDelete('cascade');
            $table->foreign('option_id')->references('id')->on('scholarship_requirement_item_options')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('scholar_response_options', function (Blueprint $table) {
            $table->dropForeign(['response_id']);
            $table->dropForeign(['option_id']);
            $table->dropColumn(['response_id', 'option_id']);
        });
        
        Schema::dropIfExists('scholar_response_options');
    }
}
