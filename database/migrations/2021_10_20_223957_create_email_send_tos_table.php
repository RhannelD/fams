<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmailSendTosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_send_tos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('email_send_id');
            $table->string('email');
            $table->boolean('sent');
            $table->timestamps();
            
            $table->foreign('email_send_id')->references('id')->on('email_sends')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('email_send_tos', function (Blueprint $table) {
            $table->dropForeign(['email_send_id']);
            $table->dropColumn(['email_send_id']);
        });
        
        Schema::dropIfExists('email_send_tos');
    }
}
