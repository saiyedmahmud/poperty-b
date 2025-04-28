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
        Schema::create('employmentStatus', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('colourValue');
            $table->longText('description')->nullable();
            $table->string('status')->default("true");
            $table->enum('isHolidayPaid', ['true', 'false'])->default('false');
            $table->enum('isWeekendPaid', ['true', 'false'])->default('false');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employmentStatus');
    }
};
