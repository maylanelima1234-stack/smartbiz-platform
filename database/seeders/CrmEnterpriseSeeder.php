<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Domain\CRM\Models\CrmPipeline;
use App\Domain\CRM\Models\CrmStage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CrmEnterpriseSeeder extends Seeder
{
    public function run(): void
    {
        Company::query()->each(function (Company $company): void {
            $pipeline = CrmPipeline::query()->firstOrCreate(
                ['company_id' => $company->getKey(), 'slug' => 'vendas'],
                [
                    'public_id' => (string) Str::ulid(),
                    'name' => 'Pipeline de Vendas',
                    'description' => 'Pipeline padrÃ£o do CRM',
                    'is_default' => true,
                    'status' => 'active',
                ]
            );

            $stages = [
                ['name' => 'Novo', 'slug' => 'novo', 'position' => 1, 'probability' => 10],
                ['name' => 'Qualificado', 'slug' => 'qualificado', 'position' => 2, 'probability' => 35],
                ['name' => 'Proposta', 'slug' => 'proposta', 'position' => 3, 'probability' => 65],
                ['name' => 'Ganho', 'slug' => 'ganho', 'position' => 4, 'probability' => 100, 'is_won' => true],
                ['name' => 'Perdido', 'slug' => 'perdido', 'position' => 5, 'probability' => 0, 'is_lost' => true],
            ];

            foreach ($stages as $stage) {
                CrmStage::query()->firstOrCreate(
                    ['pipeline_id' => $pipeline->getKey(), 'slug' => $stage['slug']],
                    array_merge([
                        'public_id' => (string) Str::ulid(),
                        'status' => 'active',
                    ], $stage)
                );
            }
        });
    }
}

