<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScholarResponseFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scholar_response_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('response_id');
            $table->foreignId('item_id');
            $table->text('file_url');
            $table->timestamps();
            
            $table->foreign('response_id')->references('id')->on('scholar_responses')->onDelete('cascade');
            $table->foreign('item_id')->references('id')->on('scholarship_requirement_items')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('scholar_response_files', function (Blueprint $table) {
            $table->dropForeign(['response_id']);
            $table->dropForeign(['item_id']);
            $table->dropColumn(['response_id', 'item_id']);
        });
        
        Schema::dropIfExists('scholar_response_files');
    }
}
