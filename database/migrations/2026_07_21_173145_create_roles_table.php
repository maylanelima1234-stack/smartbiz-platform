<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table): void {
            $table->id();
            $table->ulid('public_id')->unique();

            /*
             * NULL: papel global da plataforma.
             * Preenchido: papel personalizado de uma empresa.
             */
            $table->foreignId('company_id')
                ->nullable()
                ->constrained('companies')
                ->nullOnDelete();

            $table->string('name', 100);
            $table->string('slug', 120);
            $table->text('description')->nullable();

            $table->unsignedSmallInteger('level')->default(10);
            $table->boolean('is_system')->default(false);
            $table->string('status', 20)->default('active');

            $table->timestamps();
            $table->softDeletes();

            $table->index(['company_id', 'status']);
            $table->index(['company_id', 'slug']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
