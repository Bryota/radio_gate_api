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
        Schema::create('request_function_listener_submits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('listener_id')->nullOnDelete('cascade')->constrained('listeners');
            $table->foreignId('request_function_id')->nullOnDelete('cascade')->constrained('request_functions');
            $table->integer('point');
            $table->unique(['listener_id', 'request_function_id'], 'request_function_listener_id');
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
        Schema::dropIfExists('request_function_listener_submits');
    }
};
