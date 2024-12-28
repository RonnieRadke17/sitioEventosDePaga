<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activity;
use Illuminate\Support\Facades\Validator;

class ActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $data['activities'] = Activity::paginate(10);
        //$data['activities'] = Activity::all();
        return view('activities.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //show the activities registered
        return view('activities.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'mix' => 'required|in:0,1',
        ]);
    
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
    
        // Insertar la actividad
        Activity::create([
            'name' => $request->input('name'),
            'mix' => $request->input('mix'),
        ]);
    
        return redirect('activity')->with('mensaje', 'Evento agregado con éxito');
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
        $activity = Activity::findOrFail($id);
        return view('activities.edit', compact('activity'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $activityData = $request->except(['_token', '_method']);
        // Update the database record with the new data
        Activity::where('id', '=', $id)->update($activityData);
        return redirect('activity')->with('mensaje', 'Evento Modificado');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        Activity::destroy($id);

        return redirect('activity')->with('mensaje', 'Evento borrado');
    }
}
