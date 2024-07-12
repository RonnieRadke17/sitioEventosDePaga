<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Activity;
use App\Models\Sub;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
         // Definir un arreglo con los datos específicos
         $Activitydata = [
            ['name' => '100 mts'],
            ['name' => '200 mts'],
            ['name' => '400 mts'],
            ['name' => '800 mts'],
            ['name' => '1 km'],
            ['name' => '2 km'],
            ['name' => '3 km'],
            ['name' => '5 km'],
            ['name' => '8 km'],
            ['name' => '10 km'],
        ];

        // Insertar los registros en la base de datos
        foreach ($Activitydata as $data) {
            Activity::factory()->create($data);
        }

            // Definir un arreglo con las categorías de edad
        $SubData = [
            ['name' => 'U12'],
            ['name' => 'U13'],
            ['name' => 'U14'],
            ['name' => 'U15'],
            ['name' => 'U16'],
            ['name' => 'U17'],
            ['name' => 'U18'],
            ['name' => 'U19'],
            ['name' => 'U20'],
        ];

        // Insertar los registros en la base de datos
        foreach ($SubData as $data) {
            Sub::factory()->create($data);
        }
        
    }
}
