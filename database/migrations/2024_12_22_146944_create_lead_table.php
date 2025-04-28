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
        Schema::create('lead', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique()->nullable();
            $table->string('phone')->nullable();
            $table->unsignedBigInteger('leadOwnerId');
            $table->enum('leadStatus', ['new', 'contacted', 'qualified', 'lost', 'cancelled', 'working', 'customer'])->default('new');
            $table->unsignedBigInteger('leadSourceId')->nullable();
            $table->enum('isConverted', ['true', 'false'])->default('false');
            $table->string('status')->default("true");
            $table->double('leadValue', 8, 2)->nullable();

            $table->foreign('leadOwnerId')->references('id')->on('users');
            $table->foreign('leadSourceId')->references('id')->on('leadSource');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lead');
    }
};
