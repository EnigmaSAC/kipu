<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('custom_fields_fields', function (Blueprint $table) {
            $table->renameColumn('class', 'width');
        });

        Schema::table('custom_fields_fields', function (Blueprint $table) {
            $table->dropColumn('icon');
        });

        Schema::table('custom_fields_fields', function (Blueprint $table) {
            $table->dropColumn('type_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
