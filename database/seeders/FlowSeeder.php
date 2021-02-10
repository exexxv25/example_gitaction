<?php

namespace Database\Seeders;

use App\Models\Flow;
use Illuminate\Database\Seeder;

class FlowSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $roles = ["LICENCIA_ROL","ADMIN_ROL","MASTER_ROL","VECINO_ROL","INVITADO_ROL"];

        // $admin = [
        //     "" => "",
        // ];

        // $master = [];

        // $vecino = [];

        // $invitado = [];

        // admin roles
        // $flows = [
        //     "dashboard", => R
        //     "notificaciones", => CURD
        //     "gestiones", => CURD
        //     "unidades", => CURD
        //     "novedades", => CURD
        //     "autorizaciones", => CURD
        //     "amenities", => CURD
        //     ]

        // master roles
        // $flows = [
        //     "dashboard", => R
        //     "clientes", => CURD
        //     "administradores", => CURD
        //     "mantenimientos" => CURD

        // ];

        //vecinos
        //  "dashboard", => R
        // "documentos",
        // "licencias",
        // "autorizaciones",
        // "calendario",
        // "guia_de_servicios",
        // "clima",
        // "encuestas",
        // "camaras",
        // "galeria",
        // "estado_cc",
        // "expensas",
        // "consulta de personas",
        // "horarios"


        $flows = [
            "dashboard",
            "clientes",
            "administradores",
            "mantenimientos",
            "notificaciones",
            "gestiones",
            "unidades",
            "novedades",
            "autorizaciones",
            "amenities",
            "documentos",
            "licencias",
            "autorizaciones",
            "calendario",
            "guia_de_servicios",
            "clima",
            "encuestas",
            "camaras",
            "galeria",
            "estado_cc",
            "expensas",
            "consulta_de_personas",
            "horarios"
        ];


        foreach ($flows as $key => $value) {

            Flow::create([
                "description" => $value
            ]);
        }

    }
}
