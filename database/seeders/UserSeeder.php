<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\EmployeeDepartment;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        Department::create([
            'name' => 'IT Department',
        ]);
        Department::create([
            'name' => 'Sale Department',
        ]);

        User::create([
            'fname' => 'Administrator',
            'lname' => 'Management',
            'gender' => 0,
            'profile' => 'abc.jpg',
            'dob' => '1990-01-01',
            'place_of_birth' => 'testing',
            'current_address' => 'testing',
            'phone' => '081993407',
            'email' => 'administrator.management@gmail.com',
            'role' => 'administrator',
            'password' => hash::make('administrator.management@12345678'),
        ]);

        EmployeeDepartment::create([
            'user_Id' => User::pluck('id')->first(),
            'department_Id' => Department::pluck('id')->first(),
        ]);
    }
}
