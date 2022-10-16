<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', static function (Blueprint $table) {
            $table->foreignId('city_id')->nullable()->constrained('cities');
            $table->foreignId('speciality_id')->nullable()->constrained('specialities');
            $table->foreignId('university_id')->nullable()->constrained('universities');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('city_id');
            $table->dropConstrainedForeignId('speciality_id');
            $table->dropConstrainedForeignId('university_id');
        });
    }
}
