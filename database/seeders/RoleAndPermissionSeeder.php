<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'create-job', 'edit-job', 'delete-job', 'publish-job',
            'view-candidate', 'move-pipeline-stage', 'submit-scorecard',
            'manage-team', 'view-reports', 'manage-billing',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $roles = [
            'super_admin'    => $permissions,
            'company_admin'  => $permissions,
            'recruiter'      => ['create-job', 'edit-job', 'view-candidate', 'move-pipeline-stage', 'submit-scorecard'],
            'hiring_manager' => ['view-candidate', 'submit-scorecard', 'view-reports'],
        ];

        foreach ($roles as $roleName => $rolePermissions) {
            $role = Role::firstOrCreate(['name' => $roleName]);
            $role->syncPermissions($rolePermissions);
        }
    }
}
