<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Type;
use App\Http\Requests\TypeRequest\StoreTypeRequest;
use App\Http\Requests\TypeRequest\UpdateTypeRequest;
use App\Services\EncryptService\EncryptService;

class TypeController extends Controller
{
    protected $encryptService;

    public function __construct(EncryptService $encryptService)
    {
        $this->encryptService = $encryptService;
    }

    public function index()
    {
        $types = Type::paginate(10);
        $types = $this->encryptService->encrypt($types);
        $status = 'active';

        return view('types.index', compact('types', 'status'));
    }

    public function create()
    {
        $type = new Type();
        return view('types.form', compact('type'));
    }

    public function store(StoreTypeRequest $request)
    {
        try {
            Type::create($request->validated());
            return redirect()->route('types.index')->with('message', 'Tipo creado correctamente.');
        } catch (\Throwable $e) {
            return redirect()->route('types.index')->withErrors('Error al crear el tipo.');
        }
    }

    public function edit(string $id)
    {
        $decrypted_id = $this->encryptService->decrypt($id);
        if (!$decrypted_id) return redirect()->route('types.index')->withErrors('ID inválido.');

        $type = Type::find($decrypted_id);
        if (!$type) return redirect()->route('types.index')->withErrors('Tipo no encontrado.');

        return view('types.form', compact('type', 'id'));
    }

    public function update(UpdateTypeRequest $request, string $id)
    {
        $decrypted_id = $this->encryptService->decrypt($id);
        if (!$decrypted_id) return redirect()->route('types.index')->withErrors('ID inválido.');

        $type = Type::find($decrypted_id);
        if (!$type) return redirect()->route('types.index')->withErrors('Tipo no encontrado.');

        $type->update($request->validated());

        return redirect()->route('types.index')->with('message', 'Tipo actualizado correctamente.');
    }

    public function content($status)
    {
        
        try {
            // Elegir la consulta base según el filtro recibido
            if ($status === 'active') {
                $query = Type::query();
            }
            else if ($status === 'trashed') {
                $query = Type::onlyTrashed();
            } elseif ($status === 'all') {
                $query = Type::withTrashed();
            } else {
                // Si el tipo no es válido, redirigir o manejar el error
                return redirect()->route('types.index')->withErrors('Tipo de contenido no válido.');
            }

            // Obtener resultados paginados
            $types = $query->paginate(10);

            $types = $this->encryptService->Encrypt($types);

            // Pasar el tipo actual para marcar la selección en el frontend
            return view('types.index', compact('types','status'));

        } catch (\Throwable $th) {
            return back()->withErrors('Ocurrió un error al cargar los registros.');
        }
    }

    public function destroy(string $id)
    {
        $decrypted_id = $this->encryptService->decrypt($id);
        if (!$decrypted_id) return redirect()->route('types.index')->withErrors('ID inválido.');

        $type = Type::find($decrypted_id);
        if (!$type) return redirect()->route('types.index')->withErrors('Tipo no encontrado.');

        $type->delete();
        return redirect()->route('types.index')->with('message', 'Tipo inhabilitado correctamente.');
    }

    public function restore(string $id)
    {
        $decrypted_id = $this->encryptService->decrypt($id);
        if (!$decrypted_id) return redirect()->route('types.index')->withErrors('ID inválido.');

        $type = Type::withTrashed()->find($decrypted_id);
        if (!$type) return redirect()->route('types.index')->withErrors('Tipo no encontrado.');

        if (!$type->trashed()) return redirect()->route('types.index')->withErrors('El tipo no está inhabilitado.');

        $type->restore();
        return redirect()->route('types.index')->with('message', 'Tipo restaurado correctamente.');
    }

    public function forceDelete(string $id)
    {
        $decrypted_id = $this->encryptService->decrypt($id);
        if (!$decrypted_id) return redirect()->route('types.index')->withErrors('ID inválido.');

        $type = Type::withTrashed()->find($decrypted_id);
        if (!$type) return redirect()->route('types.index')->withErrors('Tipo no encontrado.');

        if (!$type->trashed()) return redirect()->route('types.index')->withErrors('El tipo no está inhabilitado.');

        try {
            $type->forceDelete();
            return redirect()->route('types.index')->with('message', 'Tipo eliminado permanentemente.');
        } catch (\Throwable $e) {
            return redirect()->route('types.index')->withErrors('Error al eliminar permanentemente.');
        }
    }
}

