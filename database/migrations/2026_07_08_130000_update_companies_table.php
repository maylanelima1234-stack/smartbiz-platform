<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            if (!Schema::hasColumn('companies', 'slug')) $table->string('slug')->nullable()->after('trade_name');
            if (!Schema::hasColumn('companies', 'website')) $table->string('website')->nullable()->after('owner');
            if (!Schema::hasColumn('companies', 'zip_code')) $table->string('zip_code', 20)->nullable()->after('website');
            if (!Schema::hasColumn('companies', 'address')) $table->string('address')->nullable()->after('zip_code');
            if (!Schema::hasColumn('companies', 'number')) $table->string('number', 20)->nullable()->after('address');
            if (!Schema::hasColumn('companies', 'complement')) $table->string('complement')->nullable()->after('number');
            if (!Schema::hasColumn('companies', 'district')) $table->string('district')->nullable()->after('complement');
            if (!Schema::hasColumn('companies', 'city')) $table->string('city')->nullable()->after('district');
            if (!Schema::hasColumn('companies', 'state')) $table->string('state', 2)->nullable()->after('city');
            if (!Schema::hasColumn('companies', 'logo')) $table->string('logo')->nullable()->after('state');
            if (!Schema::hasColumn('companies', 'notes')) $table->text('notes')->nullable()->after('status');
            if (!Schema::hasColumn('companies', 'created_by')) $table->foreignId('created_by')->nullable()->after('notes')->constrained('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $columns = ['slug','website','zip_code','address','number','complement','district','city','state','logo','notes','created_by'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('companies', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
