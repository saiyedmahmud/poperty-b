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
            Schema::create('project', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('projectManagerId')->nullable();
                $table->unsignedBigInteger('contactId')->nullable();
                $table->unsignedBigInteger('priorityId')->nullable();
                $table->double('projectValue', 8, 2)->nullable();
                $table->string('name');
                $table->dateTime('startDate')->nullable();
                $table->dateTime('endDate')->nullable();
                $table->longText('description')->nullable();
                $table->enum('projectStatus', ['not-started', 'in-progress', 'on-hold', 'cancelled', 'finished'])->nullable();
                $table->string('status')->default("PENDING");
                $table->timestamps();

                $table->foreign('projectManagerId')->references('id')->on('users');
                $table->foreign('contactId')->references('id')->on('contact');
                $table->foreign('priorityId')->references('id')->on('priority');
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project');
    }
};
