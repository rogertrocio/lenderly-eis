<?php

namespace Database\Seeders;

use App\Enums\Action;
use App\Enums\Module;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $modules = Module::getValues();
        $actions = Action::getValues();

        foreach ($modules as $module) {
            foreach ($actions as $action) {
                if ($module == Module::DASHBOARD && !in_array($action, [Action::ACCESS]))
                    continue;

                Permission::firstOrCreate(['name' => "$module.$action"]);
            }
        }

        /**
         * Create a Administrator role and assign all permissions to it.
         */
        $permissions = Permission::all()->pluck('id');

        $adminRole = Role::create(['name' => 'Administrator']);

        $adminRole->permissions()->attach($permissions);

        /**
         * Create a Employee role and assign Dashboard module only.
         */
        $dashboardPermission = Permission::where('name', Module::DASHBOARD . '.' . Action::ACCESS)->get()->pluck('id');

        $employeeRole = Role::create(['name' => 'Employee']);

        $employeeRole->permissions()->attach($dashboardPermission);
    }
}
