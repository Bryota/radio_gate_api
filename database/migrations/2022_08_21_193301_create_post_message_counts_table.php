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
        Schema::create('post_message_counts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('radio_program_id')->nullable()->onDelete('cascade');
            $table->foreignId('listener_my_program_id')->nullable()->onDelete('cascade');
            $table->foreignId('listener_id')->nullOnDelete('cascade')->constrained();
            $table->integer('post_counts')->default(0);
            $table->timestamps();

            $table->unique(['radio_program_id', 'listener_my_program_id', 'listener_id'], 'unique_program_id_and_listener_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('post_message_counts');
    }
};
