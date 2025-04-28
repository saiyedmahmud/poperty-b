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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('priorityId')->nullable();
            $table->unsignedBigInteger('taskTypeId')->nullable();
            $table->unsignedBigInteger('projectId')->nullable();
            $table->unsignedBigInteger('milestoneId')->nullable();
            $table->unsignedBigInteger('crmTaskStatusId')->nullable();
            $table->unsignedBigInteger('taskStatusId')->nullable();
            $table->unsignedBigInteger('teamId')->nullable();
            $table->unsignedBigInteger('assigneeId')->nullable();
            $table->unsignedBigInteger('contactId')->nullable();
            $table->unsignedBigInteger('companyId')->nullable();
            $table->unsignedBigInteger('opportunityId')->nullable();
            $table->unsignedBigInteger('quoteId')->nullable();

            $table->dateTime('startDate')->nullable();
            $table->dateTime('endDate')->nullable();
            $table->longText('description')->nullable();
            $table->string('status')->default("true");
            $table->timestamps();

            // Foreign key constraints and relation
            $table->foreign('priorityId')->references('id')->on('priority');
            $table->foreign('taskTypeId')->references('id')->on('crmTaskType');
            $table->foreign('taskStatusId')->references('id')->on('taskStatus');
            $table->foreign('crmTaskStatusId')->references('id')->on('crmTaskStatus');
            $table->foreign('assigneeId')->references('id')->on('users');
            $table->foreign('contactId')->references('id')->on('contact');
            $table->foreign('companyId')->references('id')->on('company');
            $table->foreign('opportunityId')->references('id')->on('opportunity');
            $table->foreign('quoteId')->references('id')->on('quote');
            $table->foreign('projectId')->references('id')->on('project');
            $table->foreign('milestoneId')->references('id')->on('milestone');
            $table->foreign('teamId')->references('id')->on('projectTeam');


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
