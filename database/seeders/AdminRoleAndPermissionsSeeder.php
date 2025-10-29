<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AdminRoleAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'ver usuários',
            'editar usuários',
            'deletar usuários',
            'criar usuários',

            'ver clientes',
            'editar clientes',
            'deletar clientes',
            'criar clientes',

            'ver solicitações',
            'editar solicitações',
            'deletar solicitações',
            'criar solicitações',
            'aprovar solicitações',
            'recusar solicitações',
            'cancelar solicitações',

            'ver liberações',
            'editar liberações',
            'deletar liberações',
            'criar liberações',

            'ver pagamentos',
            'editar pagamentos',
            'deletar pagamentos',
            'criar pagamentos',

            'ver empresas',
            'editar empresas',
            'deletar empresas',
            'criar empresas',

            'ver roles',
            'editar roles',
            'deletar roles',
            'criar roles',

            'ver tarefas',
            'criar tarefas',
            'editar tarefas',
            'deletar tarefas',

            'acesso painel'

        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission
            ]);
        };

        $role = Role::firstOrCreate([
            'name' => 'admin'
        ]);

        $role->syncPermissions($permissions);
    }
}
