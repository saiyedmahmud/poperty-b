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
        Schema::create('attachment', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('attachmentOwnerId')->nullable();
            $table->unsignedBigInteger('companyId')->nullable();
            $table->unsignedBigInteger('contactId')->nullable();
            $table->unsignedBigInteger('opportunityId')->nullable();
            $table->unsignedBigInteger('quoteId')->nullable();
            $table->string('attachmentPath');
            $table->string('attachmentName')->nullable();
            $table->timestamps();

            $table->foreign('attachmentOwnerId')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('companyId')->references('id')->on('company')->onDelete('cascade');
            $table->foreign('contactId')->references('id')->on('contact')->onDelete('cascade');
            $table->foreign('opportunityId')->references('id')->on('opportunity')->onDelete('cascade');
            $table->foreign('quoteId')->references('id')->on('quote')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attachment');
    }
};
