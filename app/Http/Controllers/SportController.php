<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sport;
use App\Models\Category;

class SportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $categories = Category::all(); // Obtener todas las categorías de la base de datos
        $sports = Sport::all(); // Obtener todos los deportes de la base de datos
        return view('sports.index', compact('sports','categories')); // Pasar los deportes a la vista
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        // Validar los datos de entrada
        $request->validate([
            'name' => 'required|string|max:255', // Validar que el nombre sea requerido, una cadena y tenga un máximo de 255 caracteres
        ]);

        // Crear un nuevo deporte con los datos de entrada
        $sport = new Sport([
            'name' => $request->name,
            'category_id' => $request->category_id,
        ]);

        // Guardar el deporte en la base de datos
        $sport->save();

        // Redirigir al usuario a la página de deportes
        return redirect()->route('sports.index')->with('success', 'Deporte creado correctamente.'); // Redirigir a la lista de deportes con un mensaje de éxito
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        // Obtener el deporte por ID
        $sportEdit = Sport::findOrFail($id); // Buscar el deporte por ID o lanzar un error 404 si no se encuentra
        return redirect()->back()->with($sportEdit); // Redirigir a la vista anterior con el deporte editado
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        // Validar los datos de entrada

        $request->validate([
            'name' => 'required|string|max:255', // Validar que el nombre sea requerido, una cadena y tenga un máximo de 255 caracteres
        ]);

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
        //
        // Eliminar el deporte de la base de datos
        $sport = Sport::findOrFail($id); // Buscar el deporte por ID o lanzar un error 404 si no se encuentra
        $sport->delete(); // Eliminar el deporte
        return redirect()->route('sports.index')->with('success', 'Deporte eliminado correctamente.'); // Redirigir a la lista de deportes con un mensaje de éxito
    }
}
