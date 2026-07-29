<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('leads')->where('priority', 'medium')->update(['priority' => 'normal']);
        DB::table('leads')->whereIn('source', ['Indicação', 'indicação'])->update(['source' => 'indicacao']);
        DB::table('leads')->whereIn('source', ['WhatsApp', 'Whatsapp'])->update(['source' => 'whatsapp']);
        DB::table('leads')->whereIn('source', ['Instagram Ads', 'instagram_ads'])->update(['source' => 'instagram']);
        DB::table('leads')->whereIn('source', ['Meta Ads', 'Facebook Ads', 'meta_ads'])->update(['source' => 'facebook']);
    }

    public function down(): void
    {
        // A normalização é intencional e não deve restaurar valores inconsistentes.
    }
};
