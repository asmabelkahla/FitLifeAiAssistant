<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('activity_id')
                ->constrained()
                ->onDelete('cascade');
            $table->foreignId('coach_id')
                ->nullable()
                ->constrained('users')
                ->onDelete('set null');
            $table->foreignId('room_id')
                ->nullable()
                ->constrained()
                ->onDelete('set null');
            $table->timestamp('start_time');
            $table->timestamp('end_time');
            $table->integer('max_participants');
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
        Schema::dropIfExists('sessions');
    }
}
