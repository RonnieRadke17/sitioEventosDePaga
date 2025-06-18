<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Activity;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\ActivityCollection;

use App\Http\Requests\ActivityRequest\StoreActivityRequest; // Importa la clase ActivityRequest
use App\Http\Requests\ActivityRequest\UpdateActivityRequest; // Importa la clase UpdateActivityRequest
use Illuminate\Validation\ValidationException;

class ActivityController extends Controller
{
    //
    public function index()
    {
        return new ActivityCollection(Activity::paginate(10));//ya contiene la paginación y la colección de recursos
    }

    public function store(StoreActivityRequest  $request)//ya esta validado por ActivityRequest/StoreActivityRequest y ya está
    {
        try {
            $activity = Activity::create($request->validated());

            return response()->json([
                'success' => 'Actividad creada correctamente.',
                'data' => $activity
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Ha ocurrido un error al crear la actividad.',
                'message' => $e->getMessage() // mensaje solo para pruebas
            ], 500);
        }
    }

    public function show($id)//ya está
    {
        // Validación rápida del ID antes de buscar
        if (!is_numeric($id) || (int)$id <= 0) {
            return response()->json([
                'error' => 'El ID proporcionado no es válido.'
            ], 422);
        }
        try {
            $activity = Activity::findOrFail($id);

            return response()->json([
                'data' => $activity
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Actividad no encontrada.'
            ], 404);

        } catch (\Throwable $e) {
            return response()->json([
                'error' => 'Ha ocurrido un error al obtener la actividad.',
                'message' => $e->getMessage() // útil en dev, ocúltalo en prod
            ], 500);
        }
    }

    public function update(UpdateActivityRequest $request, $id)
    {
        if (!is_numeric($id) || (int)$id <= 0) {
            return response()->json([
                'error' => 'El ID proporcionado no es válido.'
            ], 422);
        }

        try {
            $activity = Activity::findOrFail($id);
            $activity->update($request->validated());

            return response()->json([
                'success' => 'Actividad actualizada correctamente.',
                'data' => $activity
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Actividad no encontrada.'
            ], 404);

        } catch (\Throwable $e) {
            return response()->json([
                'error' => 'Ha ocurrido un error al actualizar la actividad.',
                'message' => $e->getMessage()
            ], 500);
        }
    }


    // Eliminación lógica (inhabilitar actividad)
    public function destroy($id)
    {
        $id = (int) $id;
        if ($id <= 0) {
            return response()->json(['error' => 'El ID proporcionado no es válido.'], 422);
        }

        try {
            $activity = Activity::findOrFail($id);
            $activity->delete(); // Soft delete (inhabilita)
            return response()->json(['success' => 'Actividad inhabilitada correctamente.'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Actividad no encontrada.'], 404);
        }
    }

    // Restaurar actividad eliminada lógicamente
    public function restore($id)
    {
        if (!is_numeric($id) || (int)$id <= 0) {
            return response()->json(['error' => 'El ID proporcionado no es válido.'], 422);
        }

        try {
            $activity = Activity::withTrashed()->findOrFail($id);

            if (!$activity->trashed()) {
                return response()->json(['error' => 'La actividad no está inhabilitada.'], 409);
            }

            $activity->restore();
            return response()->json(['success' => 'Actividad restaurada correctamente.', 'data' => $activity], 200);

        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Actividad no encontrada.'], 404);
        }
    }

    // Eliminación definitiva (permanente)
    public function forceDestroy($id)
    {
        if (!is_numeric($id) || (int)$id <= 0) {
            return response()->json(['error' => 'El ID proporcionado no es válido.'], 422);
        }

        try {
            $activity = Activity::withTrashed()->findOrFail($id);

            if (!$activity->trashed()) {
                return response()->json([
                    'error' => 'La actividad no está inhabilitada, no se puede eliminar permanentemente.'
                ], 409);
            }

            $activity->forceDelete();
            return response()->json(['success' => 'Actividad eliminada permanentemente.'], 200);

        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Actividad no encontrada.'], 404);
        } catch (\Throwable $e) {
            return response()->json([
                'error' => 'Error al eliminar permanentemente.',
                'message' => $e->getMessage()
            ], 500);
        }
    }


}
