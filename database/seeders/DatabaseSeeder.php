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

        /* $RoleData = [
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
        ); */


        // 1. Crear categorías
    /* $categoryNames = ['Deportes individuales', 'Deportes de equipo'];
    $categories = [];

    foreach ($categoryNames as $name) {
        $categories[$name] = Category::create(['name' => $name]);
    } */

    // 2. Crear deportes y vincular con categoría
    /* $sportsData = [
        'Atletismo' => 'Deportes individuales',
        'Fútbol' => 'Deportes de equipo',
        'Natación' => 'Deportes individuales',
        'Básquetbol' => 'Deportes de equipo',
    ];

    $sports = [];

    foreach ($sportsData as $sportName => $categoryName) {
        $sports[$sportName] = Sport::create([
            'name' => $sportName,
            'category_id' => $categories[$categoryName]->id,
        ]);
    }
 */
    // 3. Actividades de atletismo
    $Activitydata = [ 
        ['name' => '100 metros'],
        ['name' => '200 metros'],
        ['name' => '400 metros'],
        ['name' => '800 metros'],
        ['name' => '1500 metros'],
        ['name' => '5000 metros'],
        ['name' => '10000 metros'],
        ['name' => 'Maratón (42.195 km)'],
        ['name' => '110 metros con vallas'],
        ['name' => '100 metros con vallas'],
        ['name' => '400 metros con vallas'],
        ['name' => '4x100 metros'],
        ['name' => '4x400 metros'],
        ['name' => '3000 metros con obstáculos'],
        ['name' => 'Salto de longitud'],
        ['name' => 'Salto triple'],
        ['name' => 'Salto de altura'],
        ['name' => 'Salto con pértiga'],
        ['name' => 'Lanzamiento de peso'],
        ['name' => 'Lanzamiento de disco'],
        ['name' => 'Lanzamiento de martillo'],
        ['name' => 'Lanzamiento de jabalina'],
        ['name' => '20 km marcha'],
        ['name' => '35 km marcha'],
        ['name' => 'Cross Country (distancias variables)'],
        ['name' => 'Media maratón (21.0975 km)']
    ];

    foreach ($Activitydata as $data) {
        Activity::create([
            'name' => $data['name'],
            'sport_id' => $sports['Atletismo']->id,
            'mix' => false, // O true, según tu lógica
        ]);
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
        

        
        for ($i = 1; $i <= 10; $i++) {
            Place::create([
                'name' => 'Lugar ' . $i,
                'address' => 'Calle Falsa #' . rand(100, 999) . ', Ciudad Ejemplo',
                'lat' => 19.0 + ($i * 0.01), // coordenadas simuladas
                'lon' => -99.0 + ($i * 0.01),
            ]);
        }
    


    }
}
