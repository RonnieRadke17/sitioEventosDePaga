<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Activity;
use App\Models\ActivityCategory;
use App\Models\Sub;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
         // Definir un arreglo con los datos específicos de las categorías de actividades
         $ActivityCategorydata = [
            ['name' => 'Carreras de Velocidad'],
            ['name' => 'Carreras de Media Distancia'],
            ['name' => 'Carreras de Larga Distancia'],
            ['name' => 'Carreras con Vallas'],
            ['name' => 'Carreras de Relevos'],
            ['name' => 'Carreras de Obstáculos'],
            ['name' => 'Saltos'],
            ['name' => 'Lanzamientos'],
            ['name' => 'Decatlón'],
            ['name' => 'Heptatlón'],
            ['name' => 'Marcha Atlética'],
            ['name' => 'Cross Country'],
            ['name' => 'Carreras de Ruta'],
        ];

        // Insertar las categorías en la base de datos y obtener sus IDs
        $categories = [];
        foreach ($ActivityCategorydata as $data) {
            $categories[] = ActivityCategory::create($data);
        }

        // Definir un arreglo con los datos específicos de las actividades
        $Activitydata = [
            ['name' => '100 metros', 'activity_category_id' => $categories[0]->id],
            ['name' => '200 metros', 'activity_category_id' => $categories[0]->id],
            ['name' => '400 metros', 'activity_category_id' => $categories[0]->id],
            ['name' => '800 metros', 'activity_category_id' => $categories[1]->id],
            ['name' => '1500 metros', 'activity_category_id' => $categories[1]->id],
            ['name' => '5000 metros', 'activity_category_id' => $categories[2]->id],
            ['name' => '10000 metros', 'activity_category_id' => $categories[2]->id],
            ['name' => 'Maratón (42.195 km)', 'activity_category_id' => $categories[2]->id],
            ['name' => '110 metros con vallas (hombres)', 'activity_category_id' => $categories[3]->id],
            ['name' => '100 metros con vallas (mujeres)', 'activity_category_id' => $categories[3]->id],
            ['name' => '400 metros con vallas', 'activity_category_id' => $categories[3]->id],
            ['name' => '4x100 metros', 'activity_category_id' => $categories[4]->id],
            ['name' => '4x400 metros', 'activity_category_id' => $categories[4]->id],
            ['name' => '3000 metros con obstáculos', 'activity_category_id' => $categories[5]->id],
            ['name' => 'Salto de longitud', 'activity_category_id' => $categories[6]->id],
            ['name' => 'Salto triple', 'activity_category_id' => $categories[6]->id],
            ['name' => 'Salto de altura', 'activity_category_id' => $categories[6]->id],
            ['name' => 'Salto con pértiga', 'activity_category_id' => $categories[6]->id],
            ['name' => 'Lanzamiento de peso', 'activity_category_id' => $categories[7]->id],
            ['name' => 'Lanzamiento de disco', 'activity_category_id' => $categories[7]->id],
            ['name' => 'Lanzamiento de martillo', 'activity_category_id' => $categories[7]->id],
            ['name' => 'Lanzamiento de jabalina', 'activity_category_id' => $categories[7]->id],
            ['name' => '100 metros', 'activity_category_id' => $categories[8]->id],
            ['name' => 'Salto de longitud', 'activity_category_id' => $categories[8]->id],
            ['name' => 'Lanzamiento de peso', 'activity_category_id' => $categories[8]->id],
            ['name' => 'Salto de altura', 'activity_category_id' => $categories[8]->id],
            ['name' => '400 metros', 'activity_category_id' => $categories[8]->id],
            ['name' => '110 metros con vallas', 'activity_category_id' => $categories[8]->id],
            ['name' => 'Lanzamiento de disco', 'activity_category_id' => $categories[8]->id],
            ['name' => 'Salto con pértiga', 'activity_category_id' => $categories[8]->id],
            ['name' => 'Lanzamiento de jabalina', 'activity_category_id' => $categories[8]->id],
            ['name' => '1500 metros', 'activity_category_id' => $categories[8]->id],
            ['name' => '100 metros con vallas', 'activity_category_id' => $categories[9]->id],
            ['name' => 'Salto de altura', 'activity_category_id' => $categories[9]->id],
            ['name' => 'Lanzamiento de peso', 'activity_category_id' => $categories[9]->id],
            ['name' => '200 metros', 'activity_category_id' => $categories[9]->id],
            ['name' => 'Salto de longitud', 'activity_category_id' => $categories[9]->id],
            ['name' => 'Lanzamiento de jabalina', 'activity_category_id' => $categories[9]->id],
            ['name' => '800 metros', 'activity_category_id' => $categories[9]->id],
            ['name' => '20 km marcha', 'activity_category_id' => $categories[10]->id],
            ['name' => '35 km marcha', 'activity_category_id' => $categories[10]->id],
            ['name' => 'Cross Country (distancias variables)', 'activity_category_id' => $categories[11]->id],
            ['name' => 'Media maratón (21.0975 km)', 'activity_category_id' => $categories[12]->id],
            ['name' => 'Maratón (42.195 km)', 'activity_category_id' => $categories[12]->id],
        ];

        // Insertar las actividades en la base de datos
        foreach ($Activitydata as $data) {
            Activity::create($data);
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
