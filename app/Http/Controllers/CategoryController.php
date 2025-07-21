<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Requests\CategoryRequest\StoreCategoryRequest;
use App\Http\Requests\CategoryRequest\UpdateCategoryRequest;
use App\Services\EncryptService\EncryptService;

class CategoryController extends Controller
{
    protected $encryptService;

    public function __construct(EncryptService $encryptService)
    {
        $this->encryptService = $encryptService;
    }

    public function index()
    {
        $categories = Category::paginate(10);
        $categories = $this->encryptService->encrypt($categories);
        $type = 'active';

        return view('categories.index', compact('categories', 'type'));
    }

    public function create()
    {
        $category = new Category();
        return view('categories.form', compact('category'));
    }

    public function store(StoreCategoryRequest $request)
    {
        try {
            Category::create($request->validated());
            return redirect()->route('categories.index')->with('message', 'Categoría creada correctamente.');
        } catch (\Throwable $e) {
            return redirect()->route('categories.index')->withErrors('Error al crear la categoría.');
        }
    }

    public function edit(string $id)
    {
        $decrypted_id = $this->encryptService->decrypt($id);

        if (!$decrypted_id) {
            return redirect()->route('categories.index')->withErrors('ID inválido.');
        }

        $category = Category::find($decrypted_id);

        if (!$category) {
            return redirect()->route('categories.index')->withErrors('Categoría no encontrada.');
        }

        return view('categories.form', compact('category', 'id'));
    }

    public function update(UpdateCategoryRequest $request, string $id)
    {
        $decrypted_id = $this->encryptService->decrypt($id);

        if (!$decrypted_id) {
            return redirect()->route('categories.index')->withErrors('ID inválido.');
        }

        $category = Category::find($decrypted_id);

        if (!$category) {
            return redirect()->route('categories.index')->withErrors('Categoría no encontrada.');
        }

        $category->update($request->validated());

        return redirect()->route('categories.index')->with('message', 'Categoría actualizada correctamente.');
    }

    public function content($type)
    {
        try {
            if ($type === 'active') {
                $query = Category::query();
            } elseif ($type === 'trashed') {
                $query = Category::onlyTrashed();
            } elseif ($type === 'all') {
                $query = Category::withTrashed();
            } else {
                return redirect()->route('categories.index')->withErrors('Tipo de contenido no válido.');
            }

            $categories = $query->paginate(10);
            $categories = $this->encryptService->encrypt($categories);

            return view('categories.index', compact('categories', 'type'));
        } catch (\Throwable $th) {
            return back()->withErrors('Ocurrió un error al cargar las categorías.');
        }
    }

    public function destroy(string $id)
    {
        $decrypted_id = $this->encryptService->decrypt($id);

        if (!$decrypted_id) {
            return redirect()->route('categories.index')->withErrors('ID inválido.');
        }

        $category = Category::find($decrypted_id);

        if (!$category) {
            return redirect()->route('categories.index')->withErrors('Categoría no encontrada.');
        }

        $category->delete();

        return redirect()->route('categories.index')->with('message', 'Categoría inhabilitada correctamente.');
    }

    public function restore(string $id)
    {
        $decrypted_id = $this->encryptService->decrypt($id);

        if (!$decrypted_id) {
            return redirect()->route('categories.index')->withErrors('ID inválido.');
        }

        $category = Category::withTrashed()->find($decrypted_id);

        if (!$category) {
            return redirect()->route('categories.index')->withErrors('Categoría no encontrada.');
        }

        if (!$category->trashed()) {
            return redirect()->route('categories.index')->withErrors('La categoría no está inhabilitada.');
        }

        $category->restore();

        return redirect()->route('categories.index')->with('message', 'Categoría restaurada correctamente.');
    }

    public function forceDelete(string $id)
    {
        $decrypted_id = $this->encryptService->decrypt($id);

        if (!$decrypted_id) {
            return redirect()->route('categories.index')->withErrors('ID inválido.');
        }

        $category = Category::withTrashed()->find($decrypted_id);

        if (!$category) {
            return redirect()->route('categories.index')->withErrors('Categoría no encontrada.');
        }

        if (!$category->trashed()) {
            return redirect()->route('categories.index')->withErrors('La categoría no está inhabilitada, no se puede eliminar permanentemente.');
        }

        try {
            $category->forceDelete();
            return redirect()->route('categories.index')->with('message', 'Categoría eliminada permanentemente.');
        } catch (\Throwable $e) {
            return redirect()->route('categories.index')->withErrors('Error al eliminar permanentemente la categoría.');
        }
    }
}
