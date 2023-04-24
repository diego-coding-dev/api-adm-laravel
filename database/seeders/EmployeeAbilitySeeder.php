<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\EmployeeAbility;
use App\Models\Ability;

class EmployeeAbilitySeeder extends Seeder
{

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $abilities = Ability::all();

        foreach ($abilities as $ability) {
            EmployeeAbility::create([
                'employee_id' => 1,
                'ability_id' => $ability->id
            ]);
        }
    }

}
