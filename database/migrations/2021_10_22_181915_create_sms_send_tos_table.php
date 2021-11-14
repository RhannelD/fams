<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSmsSendTosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sms_send_tos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable();
            $table->foreignId('sms_send_id');
            $table->boolean('sent')->nullable()->default(null);
            $table->timestamps();
            
            $table->foreign('sms_send_id')->references('id')->on('sms_sends')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sms_send_tos', function (Blueprint $table) {
            $table->dropForeign(['sms_send_id']);
            $table->dropForeign(['user_id']);
            $table->dropColumn(['sms_send_id', 'user_id']);
        });
        
        Schema::dropIfExists('sms_send_tos');
    }
}
