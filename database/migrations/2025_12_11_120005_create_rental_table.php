<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rental', function (Blueprint $table) {
            $table->id();
            $table->foreignId('flatId')->constrained('flat')->onDelete('cascade');
            $table->foreignId('renterId')->constrained('renter')->onDelete('cascade');
            $table->date('startDate');
            $table->date('endDate')->nullable();
            $table->decimal('securityDeposit', 10, 2);
            $table->boolean('isActive')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rental');
    }
};
