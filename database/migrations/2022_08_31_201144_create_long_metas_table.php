<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLongMetasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('long_metas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('metable_id')->unsigned();
            $table->char('metable_type',50);
            $table->char('name',50);
            $table->string('value',2500);
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
        Schema::dropIfExists('long_metas');
    }
}
