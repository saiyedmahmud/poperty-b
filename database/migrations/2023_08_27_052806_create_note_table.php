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
        Schema::create('note', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('noteOwnerId');
            $table->unsignedBigInteger('contactId')->nullable();
            $table->unsignedBigInteger('companyId')->nullable();
            $table->unsignedBigInteger('opportunityId')->nullable();
            $table->unsignedBigInteger('quoteId')->nullable();
            $table->string('title');
            $table->longText('description')->nullable();
            $table->string('status')->default("true");
            $table->timestamps();

            // foreign key constraints and relation;
            $table->foreign('noteOwnerId')->references('id')->on('users');
            $table->foreign('contactId')->references('id')->on('contact');
            $table->foreign('companyId')->references('id')->on('company');
            $table->foreign('opportunityId')->references('id')->on('opportunity');
            $table->foreign('quoteId')->references('id')->on('quote');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('note');
    }
};
