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
        Schema::create('contact', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("contactOwnerId");
            $table->unsignedBigInteger("contactSourceId")->nullable();
            $table->unsignedBigInteger("contactStageId")->nullable();
            $table->string("firstName");
            $table->string("lastName")->nullable();
            $table->string("image")->nullable();
            $table->dateTime("dateOfBirth")->nullable();
            $table->unsignedBigInteger("companyId")->nullable();
            $table->string("jobTitle")->nullable();
            $table->string("department")->nullable();
            $table->unsignedBigInteger("industryId")->nullable();
            $table->string("email")->unique();
            $table->string("phone")->nullable();
            $table->string("twitter")->nullable();
            $table->string("linkedin")->nullable();
            $table->string("presentAddress")->nullable();
            $table->string("presentCity")->nullable();
            $table->string("presentZipCode")->nullable();
            $table->string("presentState")->nullable();
            $table->string("presentCountry")->nullable();
            $table->string("permanentAddress")->nullable();
            $table->string("permanentCity")->nullable();
            $table->string("permanentZipCode")->nullable();
            $table->string("permanentState")->nullable();
            $table->string("permanentCountry")->nullable();
            $table->string("description")->nullable();
            $table->string("status")->default("true");
            $table->timestamps();

            $table->foreign("contactOwnerId")->references("id")->on("users");
            $table->foreign("contactSourceId")->references("id")->on("contactSource");
            $table->foreign("contactStageId")->references("id")->on("contactStage");
            $table->foreign("companyId")->references("id")->on("company");
            $table->foreign("industryId")->references("id")->on("industry");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact');
    }
};
