<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TypeUser;

class TypeUserSeeder extends Seeder
{

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /**
         * @var array tipos de usuários
         */
        $typeUsers = [
            'admin',
            'cashier',
            'deliveryman',
            'client'
        ];

        foreach ($typeUsers as $typeUser) {
            TypeUser::create([
                'type_user' => $typeUser
            ]);
        }
    }

}
