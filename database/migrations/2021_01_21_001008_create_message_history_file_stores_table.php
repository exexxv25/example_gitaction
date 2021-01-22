<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessageHistoryFileStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('message_history_file_stores', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('fk_file_id')->nullable();
            $table->bigInteger('fk_message_id')->nullable();
            $table->string("body");
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
        Schema::dropIfExists('message_history_file_stores');
    }
}
