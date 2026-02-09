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
        Schema::create('invoice', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rentalId')->constrained('rental')->onDelete('cascade');
            $table->decimal('otherBill', 10, 2)->nullable();
            $table->decimal('rentAmount', 10, 2);
            $table->decimal('totalAmount', 10, 2);
            $table->decimal('dueAmount', 10, 2);
            $table->string('invoiceMonth');
            $table->string('status')->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice');
    }
};
