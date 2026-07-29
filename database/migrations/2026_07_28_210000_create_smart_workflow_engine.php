<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('smart_workflows', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('trigger', 80);
            $table->json('conditions')->nullable();
            $table->json('actions');
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('run_count')->default(0);
            $table->timestamp('last_run_at')->nullable();
            $table->timestamps();
            $table->index(['company_id', 'trigger', 'is_active']);
        });

        Schema::create('smart_workflow_runs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workflow_id')->constrained('smart_workflows')->cascadeOnDelete();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->nullableMorphs('subject');
            $table->string('status', 30)->default('completed');
            $table->json('context')->nullable();
            $table->json('result')->nullable();
            $table->text('error')->nullable();
            $table->timestamp('executed_at');
            $table->timestamps();
            $table->index(['company_id', 'executed_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('smart_workflow_runs');
        Schema::dropIfExists('smart_workflows');
    }
};
