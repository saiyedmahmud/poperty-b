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
        Schema::create('flat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('floorId')->constrained('floor')->onDelete('cascade');
            $table->string('flatNo');
            $table->integer('roomQty');
            $table->integer('washroomQty');
            $table->boolean('hasVeranda')->default(false);
            $table->boolean('hasKitchen')->default(false);
            $table->decimal('rent', 10, 2)->nullable();
            $table->string('status')->default('available');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flat');
    }
};
