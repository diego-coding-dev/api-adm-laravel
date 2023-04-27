<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Employee;

class EmployeeSeeder extends Seeder
{

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employee = [
            'type_user_id' => 1,
            'name' => 'funcionario 1',
            'email' => 'funcionario_1@mail.com',
            'password' => '123456',
            'is_active' => true
        ];

        Employee::create($employee);
    }

}
