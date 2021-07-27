<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScholarshipRequirementItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scholarship_requirement_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('requirement_id');
            $table->string('item');
            $table->string('type', 20);
            $table->text('note');
            $table->smallInteger('position');
            $table->timestamps();
            
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
        Schema::table('scholarship_requirement_items', function (Blueprint $table) {
            $table->dropForeign(['requirement_id']);
            $table->dropColumn(['requirement_id']);
        });
        
        Schema::dropIfExists('scholarship_requirement_items');
    }
}
