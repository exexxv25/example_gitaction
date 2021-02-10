<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvitedAutorizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invited_autorizations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('fk_user_id')->nullable();
            $table->bigInteger('fk_type_invited_id')->nullable();
            $table->bigInteger('fk_location_id')->nullable();
            $table->integer('account')->nullable();
            $table->string('message')->nullable();
            $table->string('token_invitation')->nullable()->unique();
            $table->string('phone_number')->nullable();
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
        Schema::dropIfExists('invited_autorizations');
    }
}
