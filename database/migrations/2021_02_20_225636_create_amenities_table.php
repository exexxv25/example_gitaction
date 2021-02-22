<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAmenitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('amenities', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('fk_user_id')->nullable();
            $table->bigInteger('fk_location_id')->nullable();
            $table->bigInteger('fk_type_amenities_id')->nullable();
            $table->string('name')->nullable();
            $table->integer('lot')->nullable();
            $table->string('charge')->nullable();
            $table->integer('mobile_numer')->nullable();
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
        Schema::dropIfExists('amenities');
    }
}
