<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sport;
use App\Http\Requests\SportRequest\StoreSportRequest;
use App\Http\Requests\SportRequest\UpdateSportRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\Services\EncryptService\EncryptService;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

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
        try {
            $sports = Sport::latest()->paginate(10);
            // Encriptar IDs para la vista
            $sports->getCollection()->transform(function ($item) {
                $item->encrypted_id = Crypt::encrypt($item->id);
                return $item;
            });
              $type = 'active';

            return view('sports.index', compact('sports', 'type'));
        } catch (\Exception $e) {
            Log::error($e);
            return view('sports.index')->with('error', 'Error al cargar las dependencias.');
        }

    }


    public function create(){
        return view('sports.create');
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
    public function show(String $id)
    {
        try {
            $decriptedId = Crypt::decryptString($id);
            $sport = Sport::findOrFail($decriptedId);
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

