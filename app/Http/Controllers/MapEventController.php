<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MapEventController extends Controller
{
    
    /* 
    Recibimos los parametros del mapa para validarlos y si estan validados se hace la incersion
    */
    public function verifyMapData($mapEventData)
    {
        $mapEventData = Validator::make([
            'place' => 'required|string|max:60',
            
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
