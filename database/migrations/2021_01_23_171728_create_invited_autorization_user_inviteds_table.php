<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvitedAutorizationUserInvitedsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invited_autorization_user_inviteds', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('fk_invited_autorization_id')->nullable();
            $table->boolean('autorizate')->default(1);
            $table->boolean('permanet')->default(0);
            $table->dateTime('init')->nullable();
            $table->dateTime('expired')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invited_autorization_user_inviteds');
    }
}
