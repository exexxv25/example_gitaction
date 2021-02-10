<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDataUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('lastname')->nullable();
            $table->boolean('allow')->default(1);
            $table->string('passport')->nullable();
            $table->string('phone')->nullable();
            $table->string('avatar')->nullable();
            $table->string('email')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('lastname');
            $table->dropColumn('allow');
            $table->dropColumn('passport');
            $table->dropColumn('phone');
            $table->dropColumn('avatar');
            $table->dropColumn('email');
        });
    }
}
