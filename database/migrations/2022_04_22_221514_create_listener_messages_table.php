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
        Schema::create('listener_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('radio_program_id')->nullable()->nullOnDelete()->constrained('radio_programs');
            $table->foreignId('program_corner_id')->nullable()->nullOnDelete()->constrained('program_corners');
            $table->foreignId('listener_my_program_id')->nullable()->nullOnDelete()->constrained('listener_my_programs');
            $table->foreignId('my_program_corner_id')->nullable()->nullOnDelete()->constrained('my_program_corners');
            $table->foreignId('listener_id')->nullOnDelete('cascade')->constrained('listeners');
            $table->string('subject')->nullable();
            $table->string('content');
            $table->string('radio_name')->nullable();
            $table->datetime('posted_at')->nullable();
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
        Schema::dropIfExists('listener_messages');
    }
};
