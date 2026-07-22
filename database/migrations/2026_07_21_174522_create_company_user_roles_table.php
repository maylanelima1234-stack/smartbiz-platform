<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('company_user_roles', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('company_user_id')
                ->constrained('company_user')
                ->cascadeOnDelete();

            $table->foreignId('role_id')
                ->constrained('roles')
                ->cascadeOnDelete();

            $table->foreignId('assigned_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('starts_at')->nullable();
            $table->timestamp('expires_at')->nullable();

            $table->timestamps();

            $table->unique(
                ['company_user_id', 'role_id'],
                'company_user_role_unique'
            );

            $table->index('expires_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_user_roles');
    }
};
