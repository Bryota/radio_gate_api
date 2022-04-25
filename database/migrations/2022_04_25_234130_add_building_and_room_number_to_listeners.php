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
        Schema::table('listeners', function (Blueprint $table) {
            $table->string('building')->nullable()->after('house_number');
            $table->string('room_number')->nullable()->after('building');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('listeners', function (Blueprint $table) {
            $table->dropColumn('building');
            $table->dropColumn('room_number');
        });
    }
};
