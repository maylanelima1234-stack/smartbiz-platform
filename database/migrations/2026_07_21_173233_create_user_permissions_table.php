<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_permissions', function (Blueprint $table): void {
            $table->id();
            $table->ulid('public_id')->unique();

            $table->foreignId('company_id')
                ->constrained('companies')
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('permission_id')
                ->constrained('permissions')
                ->cascadeOnDelete();

            /*
             * true: concede a permissão.
             * false: remove a permissão herdada do papel.
             */
            $table->boolean('allowed')->default(true);

            /*
             * Permite delegações temporárias.
             */
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('expires_at')->nullable();

            $table->foreignId('granted_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->text('reason')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->unique(
                ['company_id', 'user_id', 'permission_id'],
                'company_user_permission_unique'
            );

            $table->index([
                'company_id',
                'user_id',
                'allowed',
            ]);

            $table->index('expires_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_permissions');
    }
};