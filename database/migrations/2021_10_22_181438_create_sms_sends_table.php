<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSmsSendsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sms_sends', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scholarship_id');
            $table->foreignId('user_id')->nullable();
            $table->string('message', 160);
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::table('sms_sends', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['scholarship_id']);
            $table->dropColumn(['scholarship_id', 'user_id']);
        });
        
        Schema::dropIfExists('sms_sends');
    }
}
