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
        Schema::table('listener_my_programs', function (Blueprint $table) {
            $table->renameColumn('program_name', 'name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('listener_my_programs', function (Blueprint $table) {
            $table->renameColumn('name', 'program_name');
        });
    }
};
