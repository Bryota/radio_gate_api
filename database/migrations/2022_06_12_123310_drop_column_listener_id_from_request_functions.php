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
        Schema::table('request_functions', function (Blueprint $table) {
            $table->dropForeign('request_functions_listener_id_foreign');
            $table->dropColumn('listener_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('request_functions', function (Blueprint $table) {
            $table->foreignId('listener_id')->nullOnDelete('cascade')->constrained('listeners');
        });
    }
};
