<?php

namespace Database\Seeders;

use App\Models\TypeMessage;
use Illuminate\Database\Seeder;

class type_message_data extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        if(TypeMessage::all()->count() == 0){

            $data = ["consulta","reclamo","sugerencia"];

            foreach ($data as $key => $value) {

                TypeMessage::create([
                    'description' => $value,

                ]);

            }

        }

    }
}
