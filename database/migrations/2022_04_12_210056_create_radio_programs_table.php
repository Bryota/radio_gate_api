<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('radio_programs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('radio_station_id')->unsigned();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamps();
            $table->foreign('radio_station_id')->references('id')->on('radio_stations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('radio_programs');
    }
};
