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
        Schema::create('reservation', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_confirmed')->default(false);
            $table->unsignedInteger('number_of_people')->default(1);
            $table->boolean('is_paid')->default(false);

            $table->unsignedBigInteger('conference_id');
            $table->unsignedBigInteger('user_id');

            $table->foreign('conference_id')
                ->references('id')
                ->on('conference')
                ->onDelete('cascade');

            $table->foreign('user_id')
                ->references('id')
                ->on('user')
                ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservation');
    }
};
