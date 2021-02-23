<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAmenitiesReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('amenities_reservations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("fk_user_id")->nullable();
            $table->bigInteger("fk_amenities_id")->nullable();
            $table->bigInteger("fk_amenities_date")->nullable();
            $table->boolean("opened")->default(1);
            $table->date("date");
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
        Schema::dropIfExists('amenities_reservations');
    }
}
