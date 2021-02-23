<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class LotUserDataAddColumnLotUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lot_users', function (Blueprint $table) {
            $table->string("license")->nullable();
            $table->string("address")->nullable();
            $table->boolean("allow")->default(1);
            $table->string("latitude")->nullable();
            $table->string("longitude")->nullable();
            $table->string("census")->nullable();
            $table->enum('state', ['vivienda', 'obra', 'lote vacio'])->nullable();
            $table->enum('situation', ['propietario', 'inquilino'])->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lot_users', function (Blueprint $table) {
            $table->dropColumn('priority');
            $table->dropColumn("license");
            $table->dropColumn("address");
            $table->dropColumn("allow");
            $table->dropColumn("latitude");
            $table->dropColumn("longitude");
            $table->dropColumn("census");
            $table->dropColumn('state');
            $table->dropColumn('situation');
        });
    }
}
