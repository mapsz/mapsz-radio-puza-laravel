<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {            
            $table->string('email')->nullable()->change();
            $table->string('username');
            $table->string('color', 7)->nullable();
            $table->string('player', 1000)->nullable();
            $table->string('form', 1000)->nullable();
            $table->timestamp('expire')->nullable();
            
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
            $table->string('email')->nullable(false)->change();
            $table->dropColumn('username');
            $table->dropColumn('color');
            $table->dropColumn('player');
            $table->dropColumn('form');
            $table->dropColumn('expire');
        });
    }
}
