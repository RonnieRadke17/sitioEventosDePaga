<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sport;
use App\Http\Resources\SportCollection;
use App\Http\Requests\SportRequest\StoreSportRequest;
use App\Http\Requests\SportRequest\UpdateSportRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class SportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return new SportCollection(Sport::paginate(10));//ya contiene la paginación y la colección de recursos
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSportRequest $request)
    {
        try {
            $sport = Sport::create($request->validated());

            return response()->json([
                'success' => 'Deporte creado correctamente.',
                'data' => $sport
            ], 201);

        } catch (\Throwable $e) {
            return response()->json([
                'error' => 'Error al crear el deporte.',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        if (!is_numeric($id) || (int)$id <= 0) {
            return response()->json(['error' => 'El ID proporcionado no es válido.'], 422);
        }

        try {
            $sport = Sport::findOrFail($id);

            return response()->json(['data' => $sport], 200);

        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Deporte no encontrada.'], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSportRequest $request, $id)
    {
        if (!is_numeric($id) || (int)$id <= 0) {
            return response()->json(['error' => 'El ID proporcionado no es válido.'], 422);
        }

        try {
            $sport = Sport::findOrFail($id);
            $sport->update($request->validated());

            return response()->json([
                'success' => 'Deporte actualizada correctamente.',
                'data' => $sport
            ], 200);

        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Deporte no encontrada.'], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $id = (int) $id;
        if ($id <= 0) {
            return response()->json(['error' => 'El ID proporcionado no es válido.'], 422);
        }

        try {
            $Sport = Sport::findOrFail($id);
            $Sport->delete();

            return response()->json(['success' => 'Deporte inhabilitado correctamente.'], 200);

        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Deporte no encontrado.'], 404);
        }
    }

    public function restore($id)
    {
        $id = (int) $id;
        if ($id <= 0) {
            return response()->json(['error' => 'El ID proporcionado no es válido.'], 422);
        }

        try {
            $sport = Sport::withTrashed()->findOrFail($id);

            if (!$Sport->trashed()) {
                return response()->json(['error' => 'El deporte no está inhabilitado.'], 409);
            }

            $Sport->restore();

            return response()->json([
                'success' => 'Deporte restaurado correctamente.',
                'data' => $Sport
            ], 200);

        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Deporte no encontrada.'], 404);
        }
    }

    public function forceDestroy($id)
    {
        $id = (int) $id;
        if ($id <= 0) {
            return response()->json(['error' => 'El ID proporcionado no es válido.'], 422);
        }

        try {
            $Sport = Sport::withTrashed()->findOrFail($id);

            if (!$Sport->trashed()) {
                return response()->json([
                    'error' => 'El deporte no está inhabilitado, no se puede eliminar permanentemente.'
                ], 409);
            }

            $Sport->forceDelete();

            return response()->json([
                'success' => 'Deporte eliminada permanentemente.'
            ], 200);

        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Deporte no encontrada.'], 404);
        } catch (\Throwable $e) {
            return response()->json([
                'error' => 'Error al eliminar permanentemente.',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}

