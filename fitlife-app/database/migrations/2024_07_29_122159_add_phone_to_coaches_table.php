<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPhoneToCoachesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('coaches', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('coaches', function (Blueprint $table) {
            $table->dropColumn('phone');
        });
    }
}
