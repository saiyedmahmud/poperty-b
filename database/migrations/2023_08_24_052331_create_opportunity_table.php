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
        Schema::create('opportunity', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('opportunityOwnerId')->nullable();
            $table->unsignedBigInteger('contactId')->nullable();
            $table->unsignedBigInteger('companyId')->nullable();
            $table->string('opportunityName');
            $table->float('amount')->nullable();
            $table->unsignedBigInteger('opportunityTypeId')->nullable();
            $table->unsignedBigInteger('opportunityStageId')->nullable();
            $table->unsignedBigInteger('opportunitySourceId')->nullable();
            $table->dateTime('opportunityCreateDate')->nullable();
            $table->dateTime('opportunityCloseDate')->nullable();
            $table->string('nextStep')->nullable();
            $table->string('competitors')->nullable();
            $table->string('description')->nullable();
            $table->string('status')->default("true");
            $table->timestamps();

            $table->foreign('opportunityOwnerId')->references('id')->on('users');
            $table->foreign('contactId')->references('id')->on('contact');
            $table->foreign('companyId')->references('id')->on('company');
            $table->foreign('opportunityTypeId')->references('id')->on('opportunityType');
            $table->foreign('opportunityStageId')->references('id')->on('opportunityStage');
            $table->foreign('opportunitySourceId')->references('id')->on('opportunitySource');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('opportunity');
    }
};
