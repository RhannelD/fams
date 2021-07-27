<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScholarshipRequirementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scholarship_requirements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scholarship_id');
            $table->string('requirement');
            $table->text('description');
            $table->boolean('promote')->default(0);
            $table->boolean('enable')->default(0)->nullable();
            $table->timestamp('start_at')->nullable();
            $table->timestamp('end_at')->nullable();
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
        Schema::table('scholarship_requirements', function (Blueprint $table) {
            $table->dropForeign(['scholarship_id']);
            $table->dropColumn(['scholarship_id']);
        });
        
        Schema::dropIfExists('scholarship_requirements');
    }
}
