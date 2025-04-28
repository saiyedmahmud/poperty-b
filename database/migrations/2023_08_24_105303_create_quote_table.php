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
        Schema::create('quote', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('quoteOwnerId')->nullable();
            $table->unsignedBigInteger('companyId')->nullable();
            $table->unsignedBigInteger('contactId');
            $table->unsignedBigInteger('opportunityId')->nullable();
            $table->unsignedBigInteger('quoteStageId')->nullable();
            $table->string('quoteName');
            $table->dateTime('quoteDate')->nullable();
            $table->dateTime('expirationDate')->nullable();
            $table->longText('termsAndConditions')->nullable();
            $table->longText('description')->nullable();
            $table->float('discount')->nullable();
            $table->double('totalAmount')->nullable();
            $table->string('status')->default("true");
            $table->enum('isConverted', ['true', 'false'])->default('false');
            $table->timestamps();

            $table->foreign('quoteOwnerId')->references('id')->on('users');
            $table->foreign('companyId')->references('id')->on('company');
            $table->foreign('contactId')->references('id')->on('contact');
            $table->foreign('opportunityId')->references('id')->on('opportunity');
            $table->foreign('quoteStageId')->references('id')->on('quoteStage');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quote');
    }
};
