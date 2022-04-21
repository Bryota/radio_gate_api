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
        Schema::create('my_program_corners', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('listener_my_program_id')->unsigned();
            $table->string('name');
            $table->timestamps();
            $table->foreign('listener_my_program_id')->references('id')->on('listener_my_programs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('my_program_corners');
    }
};
