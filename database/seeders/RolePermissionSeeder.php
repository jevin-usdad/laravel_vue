<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $superAdmin = Role::firstOrCreate(['name' => Role::SUPER_ADMIN]);
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $manager = Role::firstOrCreate(['name' => 'manager']);

        $permissions = [
            'users.view',
            'users.create',
            'users.edit',
            'users.delete',
            'roles.view',
            'roles.create',
            'roles.edit',
            'roles.delete'
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        $superAdmin->permissions()->sync(
            Permission::pluck('id')->toArray()
        );

        $admin->permissions()->sync(
            Permission::whereIn('name', [
                'users.view',
                'users.create',
                'users.edit',
            ])->pluck('id')->toArray()
        );

        $manager->permissions()->sync(
            Permission::where('name', 'users.view')
                ->pluck('id')
                ->toArray()
        );

        $user = User::first();

        if ($user) {
            $user->roles()->sync([$superAdmin->id]);
        }
    }
}
