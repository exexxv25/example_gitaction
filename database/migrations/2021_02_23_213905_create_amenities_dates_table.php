<?php

use App\Models\AmenitiesDate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAmenitiesDatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('amenities_dates', function (Blueprint $table) {
            $table->id();
            $table->string("init");
            $table->string("expired");
            $table->timestamps();
        });

        $data = [
            (object)["init" => "12:00:00",
             "expired" => "13:00:00"],
             (object)["init" => "13:00:00",
             "expired" => "14:00:00"],
             (object)["init" => "14:00:00",
             "expired" => "15:00:00"],
             (object)["init" => "15:00:00",
             "expired" => "16:00:00"]
        ];


        foreach ($data as $key => $value) {

            AmenitiesDate::create([
                'init' => $value->init,
                'expired' => $value->expired

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
        Schema::dropIfExists('amenities_dates');
    }
}
