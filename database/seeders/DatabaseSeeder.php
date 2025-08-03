<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Sport;
use App\Models\Activity;
use App\Models\ActivityCategory;
use App\Models\Sub;
use App\Models\Role;
use App\Models\Place;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $RoleData = [
            ['name' => 'client'],
            ['name' => 'admin'],
        ];
        // Insertar los roles en la base de datos
        foreach ($RoleData as $role) {
            Role::create($role);
        }

        User::create(
            [
                'name' => 'SAAdmin',
                'paternal' => 'SAAdmin', 
                'maternal' => 'SAAdmin',
                'birthdate' => '2004-02-02',
                'gender' => "M",
                'email' => 'menesesmaturanoalexis33@gmail.com',
                'password' => base64_encode('Knives1.'),
                'role_id' => 2//revisar este apartado
            ],
        );


    
    


    }
}
