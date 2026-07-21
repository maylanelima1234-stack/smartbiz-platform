<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('company_user', function (Blueprint $table): void {
            $table->id();
            $table->ulid('public_id')->unique();

            $table->foreignId('company_id')
                ->constrained('companies')
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('role_id')
                ->nullable()
                ->constrained('roles')
                ->nullOnDelete();

            $table->string('status', 20)->default('active');
            $table->timestamp('joined_at')->nullable();
            $table->timestamp('left_at')->nullable();

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('updated_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();

            $table->unique(
                ['company_id', 'user_id'],
                'company_user_membership_unique'
            );

            $table->index(['company_id', 'status']);
            $table->index(['user_id', 'status']);
            $table->index(['company_id', 'role_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_user');
    }
};