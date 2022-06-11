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
        Schema::create('request_function_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('listener_id')->nullOnDelete('cascade')->constrained('listeners');
            $table->string('name');
            $table->text('detail');
            $table->boolean('is_open')->default(true);
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
        Schema::dropIfExists('request_function_requests');
    }
};
