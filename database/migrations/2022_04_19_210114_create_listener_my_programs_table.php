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
        Schema::create('listener_my_programs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('listener_id')->unsigned();
            $table->string('program_name');
            $table->string('email')->unique();
            $table->timestamps();
            $table->foreign('listener_id')->references('id')->on('listeners')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('listener_my_programs');
    }
};
