<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sport;
use App\Models\Category;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;


class SportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()//funciona
    {
        $categories = Category::all(); // Obtener todas las categorías de la base de datos
        $sports = Sport::with('category')->paginate(5);
        return view('sports.index', compact('sports','categories')); // Pasar los deportes a la vista
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)//ya esta
    {
        $request->validate([
            'name' => 'required|string|max:60|unique:sports,name',
           'category_id' => 'required|integer|exists:categories,id'
        ]);
        /* try catch y transaccion para crear el nuevo deporte*/
        try {//registra el sporto en la base de datos
            $sport = DB::transaction(function () use ($request) {
                return sport::create([
                    'name' => $request->name,
                    'category_id' => $request->category_id,
                ]);
            });

            if($sport){//verifica el valor de la variable $sport si tiene algo dentro lo redirecciona a la ruta de show
                return redirect()->route('sports.index')->with('success', 'Deporte creado correctamente.'); // Redirigir a la lista de deportes con un mensaje de éxito
            }else {//sino redirecciona a la ruta de sport con un mensaje de error
                return redirect()->route('sports.index')->with('error', 'Ha ocurrido un error.'); // Redirigir a la lista de deportes con un mensaje de éxito
            }

        } catch (\Exception $e) {
            return redirect()->route('sports.index')->with('error', 'Ha ocurrido un error.'); // Redirigir a la lista de deportes con un mensaje de éxito
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)//revisar codigo de update
    {
        $request->validate([
            'name' => 'required|string|min:5|max:60|unique:sports,name',
            'category_id' => 'required|integer|exists:categories,id'
        ]);

        /* try catch y transaccion para actualizar el deporte*/
        try {//registra el sporto en la base de datos
            $sport = DB::transaction(function () use ($request) {
                return sport::create([
                    'name' => $request->name,
                    'category_id' => $request->category_id,
                ]);
            });

            if($sport){//verifica el valor de la variable $sport si tiene algo dentro lo redirecciona a la ruta de show
                return redirect()->route('sports.index')->with('success', 'Deporte creado correctamente.'); // Redirigir a la lista de deportes con un mensaje de éxito
            }else {//sino redirecciona a la ruta de sport con un mensaje de error
                return redirect()->route('sports.index')->with('error', 'Ha ocurrido un error.'); // Redirigir a la lista de deportes con un mensaje de éxito
            }

        } catch (\Exception $e) {
            return redirect()->route('sports.index')->with('error', 'Ha ocurrido un error.'); // Redirigir a la lista de deportes con un mensaje de éxito
        }



        // Actualizar el deporte en la base de datos
        $sport = Sport::findOrFail($id); // Buscar el deporte por ID o lanzar una excepción si no se encuentra
        $sport->update($request->all()); // Actualizar el deporte con los datos del formulario
        return redirect()->route('sports.index')->with('success', 'Deporte actualizado correctamente.'); // Redirigir a la lista de deportes con un mensaje de éxito
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        /* usar try catch */

        $sport = Sport::findOrFail($id)->delete(); // Buscar el deporte por ID o lanzar un error 404 si no se encuentra
        return redirect()->route('sports.index')->with('success', 'Deporte eliminado correctamente.'); // Redirigir a la lista de deportes con un mensaje de éxito
    }
}
