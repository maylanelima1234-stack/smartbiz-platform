<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leads', function (Blueprint $table): void {
            if (! Schema::hasColumn('leads', 'health_score')) {
                $table->unsignedTinyInteger('health_score')->default(50)->after('score');
            }
            if (! Schema::hasColumn('leads', 'health_status')) {
                $table->string('health_status', 20)->default('attention')->after('health_score');
            }
            if (! Schema::hasColumn('leads', 'stage_entered_at')) {
                $table->timestamp('stage_entered_at')->nullable()->after('next_follow_up_at');
            }
        });

        if (! Schema::hasTable('crm_timeline_events')) {
            Schema::create('crm_timeline_events', function (Blueprint $table): void {
                $table->id();
                $table->ulid('public_id')->unique();
                $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
                $table->foreignId('lead_id')->constrained('leads')->cascadeOnDelete();
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->string('type', 60);
                $table->string('title', 180);
                $table->text('description')->nullable();
                $table->json('metadata')->nullable();
                $table->timestamps();
                $table->index(['company_id', 'lead_id', 'created_at']);
            });
        }

        if (! Schema::hasTable('crm_lead_files')) {
            Schema::create('crm_lead_files', function (Blueprint $table): void {
                $table->id();
                $table->ulid('public_id')->unique();
                $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
                $table->foreignId('lead_id')->constrained('leads')->cascadeOnDelete();
                $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
                $table->string('disk', 40)->default('local');
                $table->string('path');
                $table->string('original_name');
                $table->string('mime_type', 120)->nullable();
                $table->unsignedBigInteger('size')->default(0);
                $table->timestamps();
                $table->softDeletes();
                $table->index(['company_id', 'lead_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('crm_lead_files');
        Schema::dropIfExists('crm_timeline_events');

        Schema::table('leads', function (Blueprint $table): void {
            foreach (['health_score', 'health_status', 'stage_entered_at'] as $column) {
                if (Schema::hasColumn('leads', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
