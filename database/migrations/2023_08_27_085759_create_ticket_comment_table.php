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
        Schema::create('ticketComment', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ticketId');
            $table->string('repliedBy');
            $table->string('userType');
            $table->string('description');
            $table->string('status')->default("true");
            $table->timestamps();

            // foreign key constraints and relation;
            $table->foreign('ticketId')->references('ticketId')->on('ticket');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticketComment');
    }
};
