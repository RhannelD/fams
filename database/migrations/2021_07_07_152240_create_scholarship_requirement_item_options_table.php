<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScholarshipRequirementItemOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scholarship_requirement_item_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id');
            $table->string('option');
            $table->timestamps();
            
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
        Schema::table('scholarship_requirement_item_options', function (Blueprint $table) {
            $table->dropForeign(['item_id']);
            $table->dropColumn(['item_id']);
        });
        
        Schema::dropIfExists('scholarship_requirement_item_options');
    }
}
