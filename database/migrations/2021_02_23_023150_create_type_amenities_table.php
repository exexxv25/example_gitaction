<?php

use App\Models\TypeAmenity;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTypeAmenitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('type_amenities', function (Blueprint $table) {
            $table->id();
            $table->string("description");
            $table->timestamps();
        });

        $data = ["quincho","cancha de tenis","cancha de football 5","sum"];

        foreach ($data as $key => $value) {

            TypeAmenity::create([
                'description' => $value,

            ]);

        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('type_amenities');
    }
}
