<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolFlowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rol_flows', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('fk_user_id')->nullable();
            $table->bigInteger('fk_lot_user_id')->nullable();
            $table->bigInteger('fk_rol_id')->nullable();
            $table->bigInteger('fk_flow_permission_id')->nullable();
            $table->bigInteger('fk_type_permission_id')->nullable();
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
        Schema::dropIfExists('rol_flows');
    }
}
