<?php

namespace Database\Seeders;

use App\Models\CrmPipeline;
use App\Models\CrmStage;
use Illuminate\Database\Seeder;

class CrmPipelineSeeder extends Seeder
{
    public function run(): void
    {
        $pipeline = CrmPipeline::firstOrCreate(
            ['name' => 'Pipeline Comercial SmartBiz'],
            [
                'description' => 'Funil padrão para diagnóstico, proposta e fechamento.',
                'status' => 'Ativo',
            ]
        );

        $stages = [
            ['name' => 'Novo', 'position' => 1, 'color' => '#6D28D9'],
            ['name' => 'Contato', 'position' => 2, 'color' => '#8B5CF6'],
            ['name' => 'Diagnóstico', 'position' => 3, 'color' => '#F59E0B'],
            ['name' => 'Proposta', 'position' => 4, 'color' => '#22C55E'],
            ['name' => 'Fechado', 'position' => 5, 'color' => '#16A34A'],
        ];

        foreach ($stages as $stage) {
            CrmStage::firstOrCreate(
                [
                    'pipeline_id' => $pipeline->id,
                    'name' => $stage['name'],
                ],
                $stage + ['pipeline_id' => $pipeline->id]
            );
        }
    }
}