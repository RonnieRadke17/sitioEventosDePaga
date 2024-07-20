<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ErrorsController extends Controller
{
    //aqui mandamos un valor para saber que tipo de error mostrar en pantalla 
    public function showWindowError(Request $request){
        $message = $request->input('message');
    
        return view('errors.password-fail', compact('message'));
    }
}
