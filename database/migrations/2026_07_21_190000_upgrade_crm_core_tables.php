<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('crm_pipelines')) {
            Schema::create('crm_pipelines', function (Blueprint $table): void {
                $table->id();
                $table->ulid('public_id')->unique();
                $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
                $table->string('name', 120);
                $table->string('slug', 140);
                $table->text('description')->nullable();
                $table->boolean('is_default')->default(false);
                $table->string('status', 20)->default('active');
                $table->timestamps();
                $table->softDeletes();
                $table->unique(['company_id', 'slug']);
            });
        } else {
            Schema::table('crm_pipelines', function (Blueprint $table): void {
                if (! Schema::hasColumn('crm_pipelines', 'public_id')) $table->ulid('public_id')->nullable()->unique();
                if (! Schema::hasColumn('crm_pipelines', 'company_id')) $table->unsignedBigInteger('company_id')->nullable()->index();
                if (! Schema::hasColumn('crm_pipelines', 'slug')) $table->string('slug', 140)->nullable();
                if (! Schema::hasColumn('crm_pipelines', 'description')) $table->text('description')->nullable();
                if (! Schema::hasColumn('crm_pipelines', 'is_default')) $table->boolean('is_default')->default(false);
                if (! Schema::hasColumn('crm_pipelines', 'status')) $table->string('status', 20)->default('active');
                if (! Schema::hasColumn('crm_pipelines', 'deleted_at')) $table->softDeletes();
            });
        }

        if (! Schema::hasTable('crm_stages')) {
            Schema::create('crm_stages', function (Blueprint $table): void {
                $table->id();
                $table->ulid('public_id')->unique();
                $table->foreignId('pipeline_id')->constrained('crm_pipelines')->cascadeOnDelete();
                $table->string('name', 120);
                $table->string('slug', 140);
                $table->unsignedSmallInteger('position')->default(1);
                $table->unsignedTinyInteger('probability')->default(0);
                $table->string('color', 20)->nullable();
                $table->boolean('is_won')->default(false);
                $table->boolean('is_lost')->default(false);
                $table->string('status', 20)->default('active');
                $table->timestamps();
                $table->softDeletes();
                $table->index(['pipeline_id', 'position']);
            });
        } else {
            Schema::table('crm_stages', function (Blueprint $table): void {
                if (! Schema::hasColumn('crm_stages', 'public_id')) $table->ulid('public_id')->nullable()->unique();
                if (! Schema::hasColumn('crm_stages', 'pipeline_id')) $table->unsignedBigInteger('pipeline_id')->nullable()->index();
                if (! Schema::hasColumn('crm_stages', 'slug')) $table->string('slug', 140)->nullable();
                if (! Schema::hasColumn('crm_stages', 'position')) $table->unsignedSmallInteger('position')->default(1);
                if (! Schema::hasColumn('crm_stages', 'probability')) $table->unsignedTinyInteger('probability')->default(0);
                if (! Schema::hasColumn('crm_stages', 'color')) $table->string('color', 20)->nullable();
                if (! Schema::hasColumn('crm_stages', 'is_won')) $table->boolean('is_won')->default(false);
                if (! Schema::hasColumn('crm_stages', 'is_lost')) $table->boolean('is_lost')->default(false);
                if (! Schema::hasColumn('crm_stages', 'status')) $table->string('status', 20)->default('active');
                if (! Schema::hasColumn('crm_stages', 'deleted_at')) $table->softDeletes();
            });
        }

        if (! Schema::hasTable('leads')) {
            Schema::create('leads', function (Blueprint $table): void {
                $table->id();
                $table->ulid('public_id')->unique();
                $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
                $table->foreignId('pipeline_id')->nullable()->constrained('crm_pipelines')->nullOnDelete();
                $table->foreignId('stage_id')->nullable()->constrained('crm_stages')->nullOnDelete();
                $table->foreignId('owner_id')->nullable()->constrained('users')->nullOnDelete();
                $table->string('name', 160);
                $table->string('email')->nullable();
                $table->string('phone', 30)->nullable();
                $table->string('source', 80)->nullable();
                $table->string('status', 30)->default('open');
                $table->unsignedTinyInteger('score')->default(0);
                $table->decimal('value', 14, 2)->default(0);
                $table->text('notes')->nullable();
                $table->timestamp('last_contact_at')->nullable();
                $table->timestamp('won_at')->nullable();
                $table->timestamp('lost_at')->nullable();
                $table->timestamps();
                $table->softDeletes();
                $table->index(['company_id', 'status']);
                $table->index(['company_id', 'stage_id']);
                $table->index(['company_id', 'owner_id']);
            });
        } else {
            Schema::table('leads', function (Blueprint $table): void {
                if (! Schema::hasColumn('leads', 'public_id')) $table->ulid('public_id')->nullable()->unique();
                if (! Schema::hasColumn('leads', 'company_id')) $table->unsignedBigInteger('company_id')->nullable()->index();
                if (! Schema::hasColumn('leads', 'pipeline_id')) $table->unsignedBigInteger('pipeline_id')->nullable()->index();
                if (! Schema::hasColumn('leads', 'stage_id')) $table->unsignedBigInteger('stage_id')->nullable()->index();
                if (! Schema::hasColumn('leads', 'owner_id')) $table->unsignedBigInteger('owner_id')->nullable()->index();
                if (! Schema::hasColumn('leads', 'source')) $table->string('source', 80)->nullable();
                if (! Schema::hasColumn('leads', 'status')) $table->string('status', 30)->default('open');
                if (! Schema::hasColumn('leads', 'score')) $table->unsignedTinyInteger('score')->default(0);
                if (! Schema::hasColumn('leads', 'value')) $table->decimal('value', 14, 2)->default(0);
                if (! Schema::hasColumn('leads', 'notes')) $table->text('notes')->nullable();
                if (! Schema::hasColumn('leads', 'last_contact_at')) $table->timestamp('last_contact_at')->nullable();
                if (! Schema::hasColumn('leads', 'won_at')) $table->timestamp('won_at')->nullable();
                if (! Schema::hasColumn('leads', 'lost_at')) $table->timestamp('lost_at')->nullable();
                if (! Schema::hasColumn('leads', 'deleted_at')) $table->softDeletes();
            });
        }

        if (! Schema::hasTable('crm_activities')) {
            Schema::create('crm_activities', function (Blueprint $table): void {
                $table->id();
                $table->ulid('public_id')->unique();
                $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
                $table->foreignId('lead_id')->constrained('leads')->cascadeOnDelete();
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->string('type', 60);
                $table->string('title', 160);
                $table->text('description')->nullable();
                $table->timestamp('due_at')->nullable();
                $table->timestamp('completed_at')->nullable();
                $table->json('metadata')->nullable();
                $table->timestamps();
                $table->softDeletes();
                $table->index(['lead_id', 'created_at']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('crm_activities');
    }
};
