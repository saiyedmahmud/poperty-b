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
        Schema::create('assignedTask', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('taskId');
            $table->unsignedBigInteger('userId');
            $table->string('status')->default('true');
            $table->timestamps();

            $table->foreign('taskId')->references('id')->on('tasks')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('userId')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignedTask');
    }
};
