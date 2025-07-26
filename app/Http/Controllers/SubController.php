<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sub;

use App\Http\Requests\SubRequest\StoreSubRequest;
use App\Http\Requests\SubRequest\UpdateSubRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\Services\EncryptService\EncryptService;

class SubController extends Controller
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
        $subs = Sub::paginate(10);
        $subs = $this->encryptService->encrypt($subs);
        $type = 'active';

        return view('subs.index', compact('subs', 'type'));
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $sub = new Sub;
        return view('subs.form', compact('sub'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSubRequest $request)
    {
        try {
            Sub::create($request->validated());
            return redirect()->route('subs.index')->with('message', 'Sub creada correctamente.');
        } catch (\Throwable $e) {
            return redirect()->route('subs.index')->withErrors('Error al crear la sub.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $decrypted_id = $this->encryptService->decrypt($id);
        if (!$decrypted_id) return redirect()->route('subs.index')->withErrors('ID inválido.');

        /* buscar hasta en trasehd */
        $sub = Sub::withTrashed()->find($decrypted_id);
        if (!$sub) return redirect()->route('subs.index')->withErrors('Tipo no encontrado.');

        return view('subs.show', compact('sub'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $decrypted_id = $this->encryptService->decrypt($id);
        if (!$decrypted_id) return redirect()->route('subs.index')->withErrors('ID inválido.');

        $sub = Sub::find($decrypted_id);
        
        if (!$sub) return redirect()->route('subs.index')->withErrors('Sub no encontrada.');

        return view('subs.form', compact('sub', 'id'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSubRequest $request, string $id){

        if (empty($request->except('_token', '_method'))) {
            return redirect()->route('subs.index')->withErrors('No se han realizado cambios.');
        }

        // Verificar si el ID está encriptado y es válido
        $decrypted_id = $this->encryptService->decrypt($id);
      
        if ($decrypted_id === null) {
            return redirect()->route('subs.index')->withErrors('Error al procesar la solicitud.');
        }

        $sub = null;
        //si el id es valido, buscamos la Sub
        if($decrypted_id){
            $sub = Sub::find($decrypted_id);
            
            if (!$sub) {
                return redirect()->route('subs.index')->withErrors('Sub no encontrada.');   
            }
        }
        // Actualizar la Sub con los datos validados
        $sub->fill($request->validated());
        $sub->save();

        return redirect()->route('subs.index')->with('message', 'Sub actualizada correctamente.');
    }

    public function content($type)
    {
        
        try {
            // Elegir la consulta base según el filtro recibido
            if ($type === 'active') {
                $query = Sub::query();
            }
            else if ($type === 'trashed') {
                $query = Sub::onlyTrashed();
            } elseif ($type === 'all') {
                $query = Sub::withTrashed();
            } else {
                // Si el tipo no es válido, redirigir o manejar el error
                return redirect()->route('subs.index')->withErrors('Tipo de contenido no válido.');
            }

            // Obtener resultados paginados
            $subs = $query->paginate(10);

            $subs = $this->encryptService->Encrypt($subs);

            // Pasar el tipo actual para marcar la selección en el frontend
            return view('subs.index', compact('subs', 'type'));

        } catch (\Throwable $th) {
            return back()->withErrors('Ocurrió un error al cargar los registros.');
        }
    }

    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id){
        $decrypted_id = $this->encryptService->decrypt($id);
        if (!$decrypted_id) return redirect()->route('subs.index')->withErrors('ID inválido.');

        $sub = Sub::find($decrypted_id);
        if (!$sub) return redirect()->route('subs.index')->withErrors('Sub no encontrada.');

        $sub->delete();
        return redirect()->route('subs.index')->with('message', 'Sub eliminada correctamente.');
    }
   
     /**
     * Restore the specified soft-deleted resource.
     */
    public function restore(string $id)
    {
        $decrypted_id = $this->encryptService->decrypt($id);

        if (!$decrypted_id) {
            return redirect()->route('subs.index')->withErrors('ID inválido.');
        }

        $sub = Sub::withTrashed()->find($decrypted_id);

        if (!$sub) {
            return redirect()->route('subs.index')->withErrors('Sub no encontrada.');
        }

        if (!$sub->trashed()) {
            return redirect()->route('subs.index')->withErrors('La Sub no está inhabilitada.');
        }

        $sub->restore();

        return redirect()->route('subs.index')->with('message', 'Sub restaurada correctamente.');
    }

    /**
     * Permanently delete the specified resource.
     */
    public function forceDelete(string $id)
    {
        $decrypted_id = $this->encryptService->decrypt($id);

        if (!$decrypted_id) {
            return redirect()->route('subs.index')->withErrors('ID inválido.');
        }

        $sub = Sub::withTrashed()->find($decrypted_id);

        if (!$sub) {
            return redirect()->route('subs.index')->withErrors('Sub no encontrada.');
        }

        if (!$sub->trashed()) {
            return redirect()->route('subs.index')->withErrors('La Sub no está inhabilitada, no se puede eliminar permanentemente.');
        }

        try {
            $sub->forceDelete();
            return redirect()->route('subs.index')->with('message', 'Sub eliminada permanentemente.');
        } catch (\Throwable $e) {
            return redirect()->route('subs.index')->withErrors('Error al eliminar permanentemente: ');
        }
    }
}
