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
        Schema::create('product', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->double('rate', 8, 2)->nullable();
            $table->integer('unit')->nullable();
            $table->unsignedBigInteger('productCategoryId')->nullable();
            $table->string('status')->default('true');
            $table->index('name');
            $table->timestamps();

            $table->foreign('productCategoryId')->references('id')->on('productCategory');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product');
    }
};
