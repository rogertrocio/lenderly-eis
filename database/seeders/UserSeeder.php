<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        if (User::where('email', 'johndoe@eis.com')->doesntExist()) {
            $administrator = Role::where('name', 'Administrator')->first();

            $userAdmin = User::factory()->admin()->create();

            $userAdmin->roles()->attach($administrator->id);
        }

        if (User::where('email', 'juancruz@eis.com')->doesntExist()) {
            $employee = Role::where('name', 'Employee')->first();

            $userEmployee = User::factory()->employee()->create();

            $userEmployee->roles()->attach($employee->id);
        }
    }
}
