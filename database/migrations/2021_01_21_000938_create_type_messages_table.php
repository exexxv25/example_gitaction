<?php

use App\Models\TypeMessage;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTypeMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('type_messages', function (Blueprint $table) {
            $table->id();
            $table->string("description");
            $table->timestamps();
        });

        $data = ["consulta","reclamo","sugerencia"];

        foreach ($data as $key => $value) {

            TypeMessage::create([
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
        Schema::dropIfExists('type_messages');
    }
}
