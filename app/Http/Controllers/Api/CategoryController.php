<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Http\Resources\CategoryCollection;
use App\Http\Requests\CategoryRequest\StoreCategoryRequest;
use App\Http\Requests\CategoryRequest\UpdateCategoryRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return new CategoryCollection(Category::paginate(10));//ya contiene la paginación y la colección de recursos
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        try {
            $category = Category::create($request->validated());

            return response()->json([
                'success' => 'Categoría creada correctamente.',
                'data' => $category
            ], 201);

        } catch (\Throwable $e) {
            return response()->json([
                'error' => 'Error al crear la categoría.',
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
            $category = Category::findOrFail($id);

            return response()->json(['data' => $category], 200);

        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Categoría no encontrada.'], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, $id)
    {
        if (!is_numeric($id) || (int)$id <= 0) {
            return response()->json(['error' => 'El ID proporcionado no es válido.'], 422);
        }

        try {
            $category = Category::findOrFail($id);
            $category->update($request->validated());

            return response()->json([
                'success' => 'Categoría actualizada correctamente.',
                'data' => $category
            ], 200);

        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Categoría no encontrada.'], 404);
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
            $category = Category::findOrFail($id);
            $category->delete();

            return response()->json(['success' => 'Categoría inhabilitada correctamente.'], 200);

        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Categoría no encontrada.'], 404);
        }
    }

    public function restore($id)
    {
        $id = (int) $id;
        if ($id <= 0) {
            return response()->json(['error' => 'El ID proporcionado no es válido.'], 422);
        }

        try {
            $category = Category::withTrashed()->findOrFail($id);

            if (!$category->trashed()) {
                return response()->json(['error' => 'La categoría no está inhabilitada.'], 409);
            }

            $category->restore();

            return response()->json([
                'success' => 'Categoría restaurada correctamente.',
                'data' => $category
            ], 200);

        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Categoría no encontrada.'], 404);
        }
    }

    public function forceDestroy($id)
    {
        $id = (int) $id;
        if ($id <= 0) {
            return response()->json(['error' => 'El ID proporcionado no es válido.'], 422);
        }

        try {
            $category = Category::withTrashed()->findOrFail($id);

            if (!$category->trashed()) {
                return response()->json([
                    'error' => 'La categoría no está inhabilitada, no se puede eliminar permanentemente.'
                ], 409);
            }

            $category->forceDelete();

            return response()->json([
                'success' => 'Categoría eliminada permanentemente.'
            ], 200);

        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Categoría no encontrada.'], 404);
        } catch (\Throwable $e) {
            return response()->json([
                'error' => 'Error al eliminar permanentemente.',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}

