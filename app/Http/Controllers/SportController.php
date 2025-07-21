<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sport;
use App\Http\Requests\SportRequest\StoreSportRequest;
use App\Http\Requests\SportRequest\UpdateSportRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\Services\EncryptService\EncryptService;


class SportController extends Controller
{
    protected $encryptService;

    public function __construct(EncryptService $encryptService)
    {
        $this->encryptService = $encryptService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sports = Sport::paginate(10);
        $sports = $this->encryptService->encrypt($sports);
        $type = 'active';

        return view('sports.index', compact('sports', 'type'));
    }

    public function create()
    {
        $sport = new Sport();
        return view('sports.form', compact('sport'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSportRequest $request)
    {
        try {
            Sport::create($request->validated());

            return redirect()->route('sports.index')->with('message', 'Deporte creado correctamente.');
        } catch (\Throwable $e) {
            return redirect()->route('sports.index')->withErrors('Error al crear el deporte: ');
        }
    }

    public function edit(string $id)
    {
        $decrypted_id = $this->encryptService->decrypt($id);

        if (!$decrypted_id) {
            return redirect()->route('sports.index')->withErrors('ID inválido.');
        }

        $sport = Sport::find($decrypted_id);

        if (!$sport) {
            return redirect()->route('sports.index')->withErrors('Deporte no encontrado.');
        }

        return view('sports.form', compact('sport', 'id'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSportRequest $request, string $id)
    {
        $decrypted_id = $this->encryptService->decrypt($id);

        if (!$decrypted_id) {
            return redirect()->route('sports.index')->withErrors('ID inválido.');
        }

        $sport = Sport::find($decrypted_id);

        if (!$sport) {
            return redirect()->route('sports.index')->withErrors('Deporte no encontrado.');
        }

        $sport->update($request->validated());

        return redirect()->route('sports.index')->with('message', 'Deporte actualizado correctamente.');
    }

    public function content($type)
    {
        
        try {
            // Elegir la consulta base según el filtro recibido
            if ($type === 'active') {
                $query = Sport::query();
            }
            else if ($type === 'trashed') {
                $query = Sport::onlyTrashed();
            } elseif ($type === 'all') {
                $query = Sport::withTrashed();
            } else {
                // Si el tipo no es válido, redirigir o manejar el error
                return redirect()->route('sports.index')->withErrors('Tipo de contenido no válido.');
            }

            // Obtener resultados paginados
            $sports = $query->paginate(10);

            $sports = $this->encryptService->Encrypt($sports);

            // Pasar el tipo actual para marcar la selección en el frontend
            return view('sports.index', compact('sports', 'type'));

        } catch (\Throwable $th) {
            return back()->withErrors('Ocurrió un error al cargar los registros.');
        }
    }

    /**
     * Soft-delete the specified resource.
     */
    public function destroy(string $id)
    {
        $decrypted_id = $this->encryptService->decrypt($id);

        if (!$decrypted_id) {
            return redirect()->route('sports.index')->withErrors('ID inválido.');
        }

        $sport = Sport::find($decrypted_id);

        if (!$sport) {
            return redirect()->route('sports.index')->withErrors('Deporte no encontrado.');
        }

        $sport->delete();

        return redirect()->route('sports.index')->with('message', 'Deporte inhabilitado correctamente.');
    }

    /**
     * Restore the specified soft-deleted resource.
     */
    public function restore(string $id)
    {
        $decrypted_id = $this->encryptService->decrypt($id);

        if (!$decrypted_id) {
            return redirect()->route('sports.index')->withErrors('ID inválido.');
        }

        $sport = Sport::withTrashed()->find($decrypted_id);

        if (!$sport) {
            return redirect()->route('sports.index')->withErrors('Deporte no encontrado.');
        }

        if (!$sport->trashed()) {
            return redirect()->route('sports.index')->withErrors('El deporte no está inhabilitado.');
        }

        $sport->restore();

        return redirect()->route('sports.index')->with('message', 'Deporte restaurado correctamente.');
    }

    /**
     * Permanently delete the specified resource.
     */
    public function forceDelete(string $id)
    {
        $decrypted_id = $this->encryptService->decrypt($id);

        if (!$decrypted_id) {
            return redirect()->route('sports.index')->withErrors('ID inválido.');
        }

        $sport = Sport::withTrashed()->find($decrypted_id);

        if (!$sport) {
            return redirect()->route('sports.index')->withErrors('Deporte no encontrado.');
        }

        if (!$sport->trashed()) {
            return redirect()->route('sports.index')->withErrors('El deporte no está inhabilitado, no se puede eliminar permanentemente.');
        }

        try {
            $sport->forceDelete();
            return redirect()->route('sports.index')->with('message', 'Deporte eliminado permanentemente.');
        } catch (\Throwable $e) {
            return redirect()->route('sports.index')->withErrors('Error al eliminar permanentemente: ');
        }
    }
}

