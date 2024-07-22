<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\UserEventController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\SubController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\ErrorsController;
use App\Http\Controllers\PaypalController;
use App\Http\Controllers\MercadoPagoController;

//use App\Http\Controllers\ProfileController;Revisar si es necesario

//falta hacer uso de grupos de rutas
Route::get('/', [UserEventController::class, 'index'])->name('home');//ruta que muestra la pagina principal
Route::get('/events/{id}', [UserEventController::class, 'show'])->name('events.show');
Route::get('/purchase-event/{id}', [UserEventController::class, 'purchase'])->name('events.purchase');

//restablecimiento de contrasena
Route::controller(ResetPasswordController::class)->group(function () {
    Route::get('/password', 'showFormSendCode')->name('forgot-password');//form para envio de correo
    Route::get('/password/reset/{token}', 'showResetForm')->name('password.reset');//formulario para restablecer la contrasena
    Route::post('/password/send-passwod-code', 'sendPasswordCode')->name('password.send-password-code');//manda el correo
    Route::post('/password/reset-password', 'reset')->name('password.update');//restablece la contrasena
});
//ventana que muestra los errores comunes
Route::get('/error',[ErrorsController::class,'showWindowError'])->name('window.error');

//rutas del perfil



//falta grupo de rutas de registro y login
Route::get('/register',[RegisterController::class,'showRegistrationForm'])->name('register');
Route::post('/process-register', [RegisterController::class, 'processRegister'])->name('process-register');
Route::get('/email-verification',[RegisterController::class,'emailVerification'])->name('email-verification');
Route::post('/check-email-verification',[RegisterController::class,'checkEmailVerification'])->name('check-email-verification');
Route::post('/send-verification-code',[RegisterController::class,'sendVerificationCode'])->name('send-verification-code');
Route::post('/signup',[RegisterController::class,'register'] )->name('signup');//ruta que registra al usr

Route::view('/login', 'auth/login')->name('login');//ruta que muestra la vista del login ya no se ocupa
Route::post('/signin',[LoginController::class,'login'] )->name('signin');//ruta que inicia sesion al usr 

Route::get('/logout',[LogoutController::class,'logout'] )->name('logout');//ruta que cierra sesion al usr

Route::resource('sub', SubController::class);
Route::resource('activity', ActivityController::class);
Route::resource('event', EventController::class);

//mercadopago
Route::get('/mp', [MercadoPagoController::class, 'index']);
Route::get('/create-preference', [MercadoPagoController::class, 'generatePreferenceId']);

//paypal
Route::post('paypal', [PaypalController::class, 'paypal'])->name('paypal');
Route::get('success', [PaypalController::class, 'success'])->name('success');
Route::get('cancel', [PaypalController::class, 'cancel'])->name('cancel');

/* Route::get('/', function () {
    return view('welcome');
}); */

/*
    Route::get('/password/reset/{token}',[ResetPasswordController::class,'showResetForm'])->name('password.reset');//la ruta esta bien
    Route::get('/forgot-password',[ResetPasswordController::class,'showFormSendCode'])->name('forgot-password');
    Route::post('/send-passwod-code',[ResetPasswordController::class,'sendPasswordCode'])->name('send-passwod-code');
    Route::post('/reset-password',[ResetPasswordController::class,'reset'])->name('password.update'); 
*/