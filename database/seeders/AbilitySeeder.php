<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Ability;

class AbilitySeeder extends Seeder
{

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /**
         * @var array Definindo abilidades iniciais para um usuário que será administrador
         */
        $abilities = [
            'show-employee',
            'list-employee',
            'create-employee',
            'update-employee',
            'delete-employee',
            'show-client',
            'list-client',
            'create-client',
            'update-client',
            'delete-client',
            'show-order',
            'list-order',
            'create-order',
            'update-order',
            'delete-order',
            'show-product',
            'list-product',
            'create-product',
            'update-product',
            'delete-product',
        ];

        foreach ($abilities as $ability) {
            Ability::create([
                'ability' => $ability
            ]);
        }
    }

}