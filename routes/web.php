<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\PaypalController;

Route::get('/', function () {
    return view('welcome');
});

Route::post('paypal', [PaypalController::class, 'paypal'])->name('paypal');
Route::get('success', [PaypalController::class, 'success'])->name('success');
Route::get('cancel', [PaypalController::class, 'cancel'])->name('cancel');


Route::view('/home', 'home')->middleware('auth')->name('home');//ruta que muestra la pagina principal


Route::view('/login', 'auth/login')->name('login');//ruta que muestra la vista del login ya no se ocupa
Route::view('/register', 'auth/register')->name('register');//ruta que muestra la vista del register ya no se ocupa


Route::post('/signin',[LoginController::class,'login'] )->name('signin');//ruta que inicia sesion al usr 
Route::post('/signup',[RegisterController::class,'register'] )->name('signup');//ruta que registra al usr
Route::get('/logout',[LogoutController::class,'logout'] )->name('logout');//ruta que cierra sesion al usr


Route::resource('event', EventController::class);
