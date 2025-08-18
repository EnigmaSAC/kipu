<?php

use App\Traits\Database;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    use Database;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('custom_fields_fields', function (Blueprint $table) {
            $table->renameColumn('locations', 'location');
        });

        Schema::table('custom_fields_fields', function (Blueprint $table) {
            $sort = $table->string('sort')->after('location');

            $type = $table->string('type')->after('type_id');

            if ($this->databaseDriverIs('sqlite')) {
                $sort->nullable();

                $type->nullable();
            }
        });

        Schema::table('custom_fields_fields', function (Blueprint $table) {
            $table->integer('type_id')->nullable()->change();
        });

        Schema::table('custom_fields_field_values', function (Blueprint $table) {
            $table->dropColumn('location_id');
        });

        Schema::table('custom_fields_field_type_options', function (Blueprint $table) {
            $table->dropColumn('type_id');
        });

        Schema::table('custom_fields_field_values', function (Blueprint $table) {
            $table->dropColumn('type_id');
        });

        Schema::table('custom_fields_field_values', function (Blueprint $table) {
            $table->dropColumn('type');
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
