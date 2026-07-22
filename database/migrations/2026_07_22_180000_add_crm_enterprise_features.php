<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leads', function (Blueprint $table): void {
            if (! Schema::hasColumn('leads', 'campaign')) {
                $table->string('campaign', 160)->nullable()->after('source');
            }
            if (! Schema::hasColumn('leads', 'priority')) {
                $table->string('priority', 20)->default('normal')->after('status');
            }
            if (! Schema::hasColumn('leads', 'is_favorite')) {
                $table->boolean('is_favorite')->default(false)->after('score');
            }
            if (! Schema::hasColumn('leads', 'next_follow_up_at')) {
                $table->timestamp('next_follow_up_at')->nullable()->after('last_contact_at');
            }
        });

        if (! Schema::hasTable('crm_tags')) {
            Schema::create('crm_tags', function (Blueprint $table): void {
                $table->id();
                $table->ulid('public_id')->unique();
                $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
                $table->string('name', 80);
                $table->string('slug', 100);
                $table->string('color', 20)->default('#4F46E5');
                $table->timestamps();
                $table->softDeletes();
                $table->unique(['company_id', 'slug']);
            });
        }

        if (! Schema::hasTable('crm_lead_tag')) {
            Schema::create('crm_lead_tag', function (Blueprint $table): void {
                $table->foreignId('lead_id')->constrained('leads')->cascadeOnDelete();
                $table->foreignId('tag_id')->constrained('crm_tags')->cascadeOnDelete();
                $table->timestamps();
                $table->primary(['lead_id', 'tag_id']);
            });
        }

        if (! Schema::hasTable('crm_comments')) {
            Schema::create('crm_comments', function (Blueprint $table): void {
                $table->id();
                $table->ulid('public_id')->unique();
                $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
                $table->foreignId('lead_id')->constrained('leads')->cascadeOnDelete();
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->text('body');
                $table->timestamps();
                $table->softDeletes();
                $table->index(['lead_id', 'created_at']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('crm_comments');
        Schema::dropIfExists('crm_lead_tag');
        Schema::dropIfExists('crm_tags');
    }
};
