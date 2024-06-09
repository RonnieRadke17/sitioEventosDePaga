<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $request){
        
        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];
        
        $remember = ($request['remember'] ?? false);

        
        if(Auth::attempt($credentials, $remember)){
            $request->session()->regenerate();
            return redirect()->route('home');
        }else{
            return redirect('login');
        }

    }
}
