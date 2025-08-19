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
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('firstName')->nullable();
            $table->string('lastName')->nullable();
            $table->string('username')->unique();
            $table->string('password');
            $table->string('refreshToken')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('employeeId')->unique()->nullable();
            $table->string('phone')->nullable();
            $table->string('street')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zipCode')->nullable();
            $table->string('country')->nullable();
            $table->string('bloodGroup')->nullable();
            $table->string('image')->nullable();
            $table->uuid('roleId');
            $table->string('isLogin')->default('false');
            $table->string('status')->default('true');
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('roleId')->references('id')->on('role');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
