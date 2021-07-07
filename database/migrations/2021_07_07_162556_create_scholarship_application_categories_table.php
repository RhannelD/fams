<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScholarshipApplicationCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scholarship_application_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id');
            $table->foreignId('category_id');
            $table->timestamps();
            
            $table->foreign('application_id')->references('id')->on('scholarship_applications');
            $table->foreign('category_id')->references('id')->on('scholarship_categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('scholarship_application_categories', function (Blueprint $table) {
            $table->dropForeign(['application_id']);
            $table->dropForeign(['category_id']);
            $table->dropColumn(['application_id', 'category_id']);
        });
        
        Schema::dropIfExists('scholarship_application_categories');
    }
}
