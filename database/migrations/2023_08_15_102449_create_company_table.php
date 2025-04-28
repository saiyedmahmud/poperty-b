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
        Schema::create('company', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('companyOwnerId');
            $table->string('companyName');
            $table->string('image')->nullable();
            $table->unsignedBigInteger('industryId')->nullable();
            $table->unsignedBigInteger('companyTypeId')->nullable();
            $table->integer('companySize')->nullable();
            $table->double('annualRevenue')->nullable();
            //contact
            $table->string('website')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            //social media
            $table->text('linkedin')->nullable();
            $table->text('facebook')->nullable();
            $table->text('twitter')->nullable();
            $table->text('instagram')->nullable();
            //billing address
            $table->string('billingStreet')->nullable();
            $table->string('billingCity')->nullable();
            $table->string('billingState')->nullable();
            $table->string('billingZipCode')->nullable();
            $table->string('billingCountry')->nullable();
            //shipping address
            $table->string('shippingStreet')->nullable();
            $table->string('shippingCity')->nullable();
            $table->string('shippingState')->nullable();
            $table->string('shippingZipCode')->nullable();
            $table->string('shippingCountry')->nullable();
            $table->string('status')->default("true");
            $table->timestamps();

            $table->foreign('companyOwnerId')->references('id')->on('users');
            $table->foreign('industryId')->references('id')->on('industry');
            $table->foreign('companyTypeId')->references('id')->on('companyType');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company');
    }
};
