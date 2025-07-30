<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Services\EncryptService\EncryptService;
use App\Http\Requests\RoleRequest\StoreRoleRequest;
use App\Http\Requests\RoleRequest\UpdateRoleRequest;

class RoleController extends Controller
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
        $roles = Role::paginate(10);
        $roles = $this->encryptService->encrypt($roles);
        $type = 'active';

        return view('roles.index', compact('roles', 'type'));
    }
    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {         
        $role = new Role();
        return view('roles.form', compact('role'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoleRequest $request)
    {
        try {
            Role::create($request->validated());

            return redirect()->route('roles.index')->with('message', 'Deporte creado correctamente.');
        } catch (\Throwable $e) {
            return redirect()->route('roles.index')->withErrors('Error al crear el deporte: ');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id){
        $decrypted_id = $this->encryptService->decrypt($id);
        if (!$decrypted_id) return redirect()->route('roles.index')->withErrors('ID inválido.');

        $role = Role::find($decrypted_id);
        
        if (!$role) return redirect()->route('roles.index')->withErrors('Evento no encontrada.');

        return view('roles.form', compact('role', 'id'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoleRequest $request, string $id)
    {
        $decrypted_id = $this->encryptService->decrypt($id);

        if (!$decrypted_id) {
            return redirect()->route('roles.index')->withErrors('ID inválido.');
        }

        $role = Role::find($decrypted_id);

        if (!$role) {
            return redirect()->route('roles.index')->withErrors('Deporte no encontrado.');
        }

        $role->update($request->validated());

        return redirect()->route('roles.index')->with('message', 'Deporte actualizado correctamente.');
    }

    public function content($type)
    {
        
        try {
            // Elegir la consulta base según el filtro recibido
            if ($type === 'active') {
                $query = Role::query();
            }
            else if ($type === 'trashed') {
                $query = Role::onlyTrashed();
            } elseif ($type === 'all') {
                $query = Role::withTrashed();
            } else {
                // Si el tipo no es válido, redirigir o manejar el error
                return redirect()->route('roles.index')->withErrors('Tipo de contenido no válido.');
            }

            // Obtener resultados paginados
            $roles = $query->paginate(10);

            $roles = $this->encryptService->Encrypt($roles);

            // Pasar el tipo actual para marcar la selección en el frontend
            return view('roles.index', compact('roles', 'type'));

        } catch (\Throwable $th) {
            return back()->withErrors('Ocurrió un error al cargar los registros.');
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $decrypted_id = $this->encryptService->decrypt($id);

        if (!$decrypted_id) {
            return redirect()->route('roles.index')->withErrors('ID inválido.');
        }

        $role = Role::find($decrypted_id);

        if (!$role) {
            return redirect()->route('roles.index')->withErrors('Deporte no encontrado.');
        }

        $role->delete();

        return redirect()->route('roles.index')->with('message', 'Deporte inhabilitado correctamente.');
    }

    /**
     * Restore the specified soft-deleted resource.
     */
    public function restore(string $id)
    {
        $decrypted_id = $this->encryptService->decrypt($id);

        if (!$decrypted_id) {
            return redirect()->route('roles.index')->withErrors('ID inválido.');
        }

        $role = Role::withTrashed()->find($decrypted_id);

        if (!$role) {
            return redirect()->route('roles.index')->withErrors('Deporte no encontrado.');
        }

        if (!$role->trashed()) {
            return redirect()->route('roles.index')->withErrors('El deporte no está inhabilitado.');
        }

        $role->restore();

        return redirect()->route('roles.index')->with('message', 'Deporte restaurado correctamente.');
    }

    /**
     * Permanently delete the specified resource.
     */
    public function forceDelete(string $id)
    {
        $decrypted_id = $this->encryptService->decrypt($id);

        if (!$decrypted_id) {
            return redirect()->route('roles.index')->withErrors('ID inválido.');
        }

        $role = Role::withTrashed()->find($decrypted_id);

        if (!$role) {
            return redirect()->route('roles.index')->withErrors('Deporte no encontrado.');
        }

        if (!$role->trashed()) {
            return redirect()->route('roles.index')->withErrors('El deporte no está inhabilitado, no se puede eliminar permanentemente.');
        }

        try {
            $role->forceDelete();
            return redirect()->route('roles.index')->with('message', 'Deporte eliminado permanentemente.');
        } catch (\Throwable $e) {
            return redirect()->route('roles.index')->withErrors('Error al eliminar permanentemente: ');
        }
    }

}
