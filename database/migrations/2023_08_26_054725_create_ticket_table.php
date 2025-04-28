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
        Schema::create('ticket', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ticketId')->unique()->nullable();
            $table->unsignedBigInteger('customerId')->nullable();
            $table->string('email')->nullable();
            $table->string('subject');
            $table->string('description')->nullable();
            $table->string('ticketResolveTime')->nullable();
            $table->unsignedBigInteger('ticketCategoryId')->nullable();
            $table->unsignedBigInteger('priorityId')->nullable();
            $table->unsignedBigInteger('ticketStatusId')->nullable();
            $table->string('status')->default("true");
            $table->timestamps();

            // Foreign key constraints and relation
            $table->foreign('customerId')->references('id')->on('customer');
            $table->foreign('ticketCategoryId')->references('id')->on('ticketCategory');
            $table->foreign('priorityId')->references('id')->on('priority');
            $table->foreign('ticketStatusId')->references('id')->on('ticketStatus');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket');
    }
};
