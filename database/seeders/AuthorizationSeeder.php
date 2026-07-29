<?php

namespace Database\Seeders;

use App\Core\Authorization\Enums\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AuthorizationSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        foreach (Permission::cases() as $permission) {
            [$module, $action] = array_pad(explode('.', $permission->value, 2), 2, 'access');

            DB::table('permissions')->updateOrInsert(
                ['slug' => $permission->value],
                [
                    'public_id' => (string) Str::ulid(),
                    'module' => $module,
                    'action' => $action,
                    'description' => $this->description($permission),
                    'is_system' => true,
                    'status' => 'active',
                    'updated_at' => $now,
                    'created_at' => $now,
                    'deleted_at' => null,
                ]
            );
        }

        $roles = [
            'admin_cto' => [
                'name' => 'Adm. CTO',
                'description' => 'Controle total da plataforma, segurança, usuários e configurações globais.',
                'level' => 100,
                'permissions' => array_map(fn (Permission $p) => $p->value, Permission::cases()),
            ],
            'admin_tm' => [
                'name' => 'Adm. Tráfego e Marketing',
                'description' => 'Operação de carteira, CRM, marketing, atendimentos, agenda, relatórios e suporte.',
                'level' => 70,
                'permissions' => [
                    Permission::DASHBOARD_VIEW->value,
                    Permission::COMPANIES_VIEW->value,
                    Permission::CRM_VIEW->value,
                    Permission::CRM_CREATE->value,
                    Permission::CRM_UPDATE->value,
                    Permission::MARKETING_VIEW->value,
                    Permission::MARKETING_MANAGE->value,
                    Permission::ATTENDANCES_VIEW->value,
                    Permission::ATTENDANCES_TAKEOVER->value,
                    Permission::ATTENDANCES_TRANSFER->value,
                    Permission::ATTENDANCES_VIEW_RAQUEL_PROGRESS->value,
                    Permission::AGENDA_VIEW->value,
                    Permission::REPORTS_VIEW->value,
                    Permission::SUPPORT_VIEW->value,
                ],
            ],
        ];

        foreach ($roles as $slug => $definition) {
            DB::table('roles')->updateOrInsert(
                ['company_id' => null, 'slug' => $slug],
                [
                    'public_id' => (string) Str::ulid(),
                    'name' => $definition['name'],
                    'description' => $definition['description'],
                    'level' => $definition['level'],
                    'is_system' => true,
                    'status' => 'active',
                    'updated_at' => $now,
                    'created_at' => $now,
                    'deleted_at' => null,
                ]
            );

            $roleId = DB::table('roles')->whereNull('company_id')->where('slug', $slug)->value('id');
            $permissionIds = DB::table('permissions')
                ->whereIn('slug', $definition['permissions'])
                ->pluck('id');

            DB::table('role_permissions')->where('role_id', $roleId)->delete();

            foreach ($permissionIds as $permissionId) {
                DB::table('role_permissions')->insert([
                    'role_id' => $roleId,
                    'permission_id' => $permissionId,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }

    private function description(Permission $permission): string
    {
        return match ($permission) {
            Permission::FINANCE_DASHBOARD_VIEW => 'Visualizar painel financeiro.',
            Permission::FINANCE_INVOICES_VIEW => 'Visualizar faturas e cobranças.',
            Permission::FINANCE_INVOICES_CREATE => 'Criar faturas e cobranças.',
            Permission::FINANCE_PAYMENTS_VIEW => 'Visualizar pagamentos.',
            Permission::FINANCE_PAYMENTS_MANAGE => 'Administrar pagamentos.',
            Permission::FINANCE_PLANS_VIEW => 'Visualizar planos contratados.',
            Permission::FINANCE_PLANS_EDIT => 'Alterar planos e condições comerciais.',
            Permission::FINANCE_SUBSCRIPTIONS_VIEW => 'Visualizar assinaturas.',
            Permission::FINANCE_REPORTS_VIEW => 'Visualizar relatórios financeiros.',
            Permission::SMARTBOT_MANAGE => 'Configurar o SmartBot e seus recursos técnicos.',
            Permission::MARKETPLACE_VIEW => 'Acessar o Marketplace.',
            default => 'Permissão de sistema: '.$permission->value.'.',
        };
    }
}
