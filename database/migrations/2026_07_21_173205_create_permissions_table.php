<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('permissions', function (Blueprint $table): void {
            $table->id();
            $table->ulid('public_id')->unique();

            $table->string('module', 80);
            $table->string('action', 80);
            $table->string('slug', 160)->unique();
            $table->text('description')->nullable();

            $table->boolean('is_system')->default(true);
            $table->string('status', 20)->default('active');

            $table->timestamps();
            $table->softDeletes();

            $table->index(['module', 'action']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }
};