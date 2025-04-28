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
        Schema::create('saleInvoice', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->dateTime('date');
            $table->string('invoiceMemoNo')->nullable();
            $table->double('totalAmount');
            $table->double('totalTaxAmount');
            $table->double('totalDiscountAmount');
            $table->double('paidAmount');
            $table->double('dueAmount');
            $table->dateTime('dueDate')->nullable();
            $table->unsignedBigInteger('contactId');
            $table->unsignedBigInteger('companyId')->nullable();
            $table->unsignedBigInteger('userId');
            $table->string('note')->nullable();
            $table->string('address')->nullable();
            $table->string('termsAndConditions')->nullable();
            $table->enum('paymentStatus', ['paid', 'due'])->default('due');
            $table->string('status')->default('true');
            $table->timestamps();

            // foreign key
            $table->foreign('userId')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('contactId')->references('id')->on('contact')->onDelete('cascade');
            $table->foreign('companyId')->references('id')->on('company')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('saleInvoice');
    }
};
