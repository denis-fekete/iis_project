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
        Schema::create('conference', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('theme');
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->string('place_address');
            $table->decimal('price', 10, 2);
            $table->unsignedInteger('capacity');

            $table->unsignedBigInteger('owner_id');

            $table->foreign('owner_id')
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
        Schema::dropIfExists('conference');
    }
};
