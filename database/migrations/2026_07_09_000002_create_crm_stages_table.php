<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('crm_stages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pipeline_id')->constrained('crm_pipelines')->cascadeOnDelete();
            $table->string('name');
            $table->integer('position')->default(0);
            $table->string('color')->default('#6D28D9');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('crm_stages');
    }
};

