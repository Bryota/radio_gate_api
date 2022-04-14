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
        Schema::create('program_corners', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('radio_program_id')->unsigned();
            $table->string('name');
            $table->timestamps();
            $table->foreign('radio_program_id')->references('id')->on('radio_programs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('program_corners');
    }
};
