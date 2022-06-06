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
        Schema::table('listener_messages', function (Blueprint $table) {
            $table->boolean('user_info_flag')->default(false)->after('radio_name');
            $table->boolean('tel_flag')->default(false)->after('user_info_flag');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('listener_messages', function (Blueprint $table) {
            $table->dropColumn('user_info_flag');
            $table->dropColumn('tel_flag');
        });
    }
};
