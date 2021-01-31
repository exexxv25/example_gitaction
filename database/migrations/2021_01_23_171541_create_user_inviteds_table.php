<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserInvitedsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_inviteds', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('fk_provider_type_service_id')->nullable();
            $table->bigInteger('fk_provider_heading_id')->nullable();
            $table->boolean('allow')->default(1);
            $table->string('name')->nullable();
            $table->string('lastname')->nullable();
            $table->string('nickname')->nullable();
            $table->integer('passport')->nullable();
            $table->integer('phone_number')->nullable();
            $table->boolean('frequent')->default(0);
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
        Schema::dropIfExists('user_inviteds');
    }
}
