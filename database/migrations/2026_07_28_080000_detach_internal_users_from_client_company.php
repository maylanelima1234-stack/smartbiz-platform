<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('users')
            ->whereIn('role', ['super_admin', 'admin_cto', 'admin_tm'])
            ->update(['company_id' => null]);
    }

    public function down(): void
    {
        // O vínculo antigo não é restaurado porque perfis internos são multiempresa.
    }
};
