<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScholarshipApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scholarship_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scholarship_id');
            $table->foreignId('requirement_id');
            $table->boolean('promote')->default(0);
            $table->timestamp('start_at')->nullable();
            $table->timestamp('end_at')->nullable();
            $table->timestamps();
            
            $table->foreign('scholarship_id')->references('id')->on('scholarships');
            $table->foreign('requirement_id')->references('id')->on('scholarship_requirements');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('scholarship_applications', function (Blueprint $table) {
            $table->dropForeign(['scholarship_id']);
            $table->dropForeign(['requirement_id']);
            $table->dropColumn(['scholarship_id', 'requirement_id']);
        });
        
        Schema::dropIfExists('scholarship_applications');
    }
}
