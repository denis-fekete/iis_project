<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('lecture', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('poster');
            $table->boolean('is_confirmed');
            $table->dateTime('start_time');
            $table->dateTime('end_time');

            $table->unsignedBigInteger('conference_id');
            $table->unsignedBigInteger('speaker_id');
            $table->unsignedBigInteger('room_id');
            
            $table->foreign('conference_id')
                ->references('id')
                ->on('conference')
                ->onDelete('cascade');

            $table->foreign('speaker_id')
                ->references('id')
                ->on('user')
                ->onDelete('cascade');

            $table->foreign('room_id')
                ->references('id')
                ->on('room')
                ->onDelete('cascade');


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lecture');
    }
};
