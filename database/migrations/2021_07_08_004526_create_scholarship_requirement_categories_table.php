<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScholarshipRequirementCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scholarship_requirement_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('requirement_id');
            $table->foreignId('category_id');
            $table->timestamps();
            
            $table->foreign('requirement_id')->references('id')->on('scholarship_requirements')->onDelete('cascade');
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
        Schema::table('scholarship_requirement_categories', function (Blueprint $table) {
            $table->dropForeign(['requirement_id']);
            $table->dropForeign(['category_id']);
            $table->dropColumn(['requirement_id', 'category_id']);
        });
        
        Schema::dropIfExists('scholarship_requirement_categories');
    }
}
