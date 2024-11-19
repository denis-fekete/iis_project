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
        Schema::create('lecture_schedule', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('reservation_id');
            $table->unsignedBigInteger('lecture_id');

            $table->foreign('reservation_id')
                ->references('id')
                ->on('reservation')
                ->onDelete('cascade');
            
            $table->foreign('lecture_id')
                ->references('id')
                ->on('lecture')
                ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lecture_schedule');
    }
};
