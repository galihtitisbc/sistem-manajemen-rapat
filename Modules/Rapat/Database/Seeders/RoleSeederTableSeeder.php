<?php

namespace Modules\Rapat\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeederTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $roles = ['pimpinan', 'pejabat', 'sekretaris', 'kepegawaian', 'dosen'];
        $permissions = ['index', 'create', 'store', 'show', 'edit', 'update', 'destroy'];

        foreach ($roles as $roleName) {
            $role = Role::firstOrCreate(['name' => $roleName]);
            $permissionExist = [
                'adminlte.darkmode.toggle',
                'home.index',
                'login.show',
                'logout.perform',
                'login.perform',
                'ssoLogin',
                'beralih.peran',
                'users.tukaruser',
                'users.editprofile',
                'users.updateprofile'
            ];
            $role->syncPermissions($permissionExist);
            foreach ($permissions as $action) {
                $permissionName = "{$roleName}.{$action}";
                $permission = Permission::firstOrCreate(['name' => $permissionName]);
                $role->givePermissionTo($permission);
            }
        }
    }
}
