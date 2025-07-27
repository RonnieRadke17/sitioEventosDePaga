<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\UserEventController;
use App\Http\Controllers\ActivityEventController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\ErrorsController;
use App\Http\Controllers\PaypalController;
use App\Http\Controllers\MercadoPagoController;
use App\Http\Controllers\AdminEventController;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\EventMapController;
use App\Http\Controllers\ImageEventController;


use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SportController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\SubController;
use App\Http\Controllers\TypeController;
use App\Http\Controllers\ActivityTypeController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\CategoryEventController;

Route::resource('sports', SportController::class)->except('show');
Route::get('/sports/content/{type}', [SportController::class, 'content'])->name('sports.content');
Route::post('/sports/{id}/restore', [SportController::class, 'restore'])->name('sports.restore');
Route::delete('/sports/{id}/force-delete', [SportController::class, 'forceDelete'])->name('sports.forceDelete');

Route::resource('categories', CategoryController::class)->except('show');
Route::get('/categories/content/{type}', [CategoryController::class, 'content'])->name('categories.content');
Route::post('/categories/{id}/restore', [CategoryController::class, 'restore'])->name('categories.restore');
Route::delete('/categories/{id}/force-delete', [CategoryController::class, 'forceDelete'])->name('categories.forceDelete');

Route::resource('types', TypeController::class)->except('show');
Route::get('/types/content/{type}', [TypeController::class, 'content'])->name('types.content');
Route::post('/types/{id}/restore', [TypeController::class, 'restore'])->name('types.restore');
Route::delete('/types/{id}/force-delete', [TypeController::class, 'forceDelete'])->name('types.forceDelete');


Route::resource('activities', ActivityController::class);
Route::get('/activities/content/{type}', [ActivityController::class, 'content'])->name('activities.content');
Route::post('/activities/{id}/restore', [ActivityController::class, 'restore'])->name('activities.restore');
Route::delete('/activities/{id}/force-delete', [ActivityController::class, 'forceDelete'])->name('activities.forceDelete');

Route::resource('subs', SubController::class);
Route::get('/subs/content/{type}', [SubController::class, 'content'])->name('subs.content');
Route::post('/subs/{id}/restore', [SubController::class, 'restore'])->name('subs.restore');
Route::delete('/subs/{id}/force-delete', [SubController::class, 'forceDelete'])->name('subs.forceDelete');


Route::get('activity-types/form/{activity}', [ActivityTypeController::class, 'form'])->name('activity-types.form');
Route::post('activity-types/store', [ActivityTypeController::class, 'store'])->name('activity-types.store');
Route::patch('activity-types/update{id}', [ActivityTypeController::class, 'update'])->name('activity-types.update');

/* falta reviar estas rutas */

Route::resource('events', EventController::class); 
Route::get('/events/content/{type}', [EventController::class, 'content'])->name('event.content');
Route::post('/events/{id}/restore', [EventController::class, 'restore'])->name('event.restore');
Route::delete('/events/{id}/force-delete', [EventController::class, 'forceDelete'])->name('event.forceDelete');

Route::get('category-events/form/{event}', [ActivityTypeController::class, 'form'])->name('category-events.form');
Route::post('category-events/store', [ActivityTypeController::class, 'store'])->name('category-events.store');
Route::patch('category-events/update{id}', [ActivityTypeController::class, 'update'])->name('category-events.update');


/* por hacer aun*/
Route::resource('places', PlaceController::class);
Route::get('/places/content/{type}', [PlaceController::class, 'content'])->name('places.content');
Route::post('/places/{id}/restore', [PlaceController::class, 'restore'])->name('places.restore');
Route::delete('/places/{id}/force-delete', [PlaceController::class, 'forceDelete'])->name('places.forceDelete');


/* ya después de hacer lo del mapa se tiene que agregar una ruta para que se pueda agregar el lugar a un evento +
    podrian ser la ruta del form y la del store y update en dado caso de que tenga una  */






//rutas del mapa de los eventos
Route::resource('event-map', EventMapController::class)->except(['index', 'create']); // Excluye index y create
// Ruta personalizada para create con parámetro id
Route::get('/event-map/create/{id}', [EventMapController::class, 'create'])->name('event-map.create');

// Resource sin index
Route::resource('activities-event', ActivityEventController::class)->except(['index', 'create']); // Excluye index y create
// Ruta personalizada para create con parámetro id
Route::get('/activities-event/create/{id}', [ActivityEventController::class, 'create'])->name('activities-event.create');


// Resource sin index
Route::resource('images-event', ImageEventController::class)->except(['index', 'create']); // Excluye index y create
// Ruta personalizada para create con parámetro id
Route::get('/images-event/create/{id}', [ImageEventController::class, 'create'])->name('images-event.create');


Route::get('/pago', function () {
    return view('stripe');
})->name('stripe.form');

Route::post('/pago', [StripeController::class, 'processPayment'])->name('stripe.payment'); 

//use App\Http\Controllers\ProfileController;Revisar si es necesario

 Route::get('payment', function () {//ventana que muestra paypal
    return view('welcome');
});

//falta hacer uso de grupos de rutas
Route::get('/', [UserEventController::class, 'index'])->name('home');//ruta que muestra la pagina principal
Route::get('/events/{id}', [UserEventController::class, 'show'])->name('eventDetails.show');
Route::post('/inscription-free/{id}', [UserEventController::class, 'inscriptionFree'])->name('events.inscriptionFree');
Route::post('/confirmPayment/{id}', [UserEventController::class, 'confirmPayment'])->name('events.confirmPayment');

//restablecimiento de contrasena
 Route::controller(ResetPasswordController::class)->group(function () {
    Route::get('/password', 'showFormSendCode')->name('forgot-password');//form para envio de correo
    Route::get('/password/reset/{token}', 'showResetForm')->name('password.reset');//formulario para restablecer la contrasena
    Route::post('/password/send-passwod-code', 'sendPasswordCode')->name('password.send-password-code');//manda el correo
    Route::post('/password/reset-password', 'reset')->name('password.update');//restablece la contrasena
    Route::post('/password/send-code-password', 'reSendPasswordCode')->name('password.send-code');//manda el correo
    //ruta de ventana cuenta bloqueada
});

Route::view('acount/suspended', 'auth/passwords/message')->name('acount.suspended');


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

//Route::view('/login', 'auth/login')->name('login');//ruta que muestra la vista del login ya no se ocupa
Route::get('/login',[LoginController::class,'showLoginForm'])->name('login');//ruta que muestra la vista del login

Route::post('/signin',[LoginController::class,'login'] )->name('signin');//ruta que inicia sesion al usr 

Route::get('/logout',[LogoutController::class,'logout'] )->name('logout');//ruta que cierra sesion al usr

   
   
    

/*  Route::resource('event', EventController::class)->middleware(RoleMiddleware::class);
Route::resource('sub', SubController::class)->middleware(RoleMiddleware::class);
Route::resource('activity', ActivityController::class)->middleware(RoleMiddleware::class); */

//mercadopago
Route::get('/mp', [MercadoPagoController::class, 'index']);
Route::get('/create-preference', [MercadoPagoController::class, 'generatePreferenceId']);

//paypal
Route::post('paypal', [PaypalController::class, 'paypal'])->name('paypal');
Route::get('success', [PaypalController::class, 'success'])->name('success');
Route::get('cancel', [PaypalController::class, 'cancel'])->name('cancel');



/* 
//User Event
// Ruta para ver los eventos en los que el usuario está registrado
Route::get('/UserEvent', [UserEventController::class, 'userRegisteredEvents'])->name('user.events');
// Ruta para mostrar los detalles de un evento registrado
Route::get('/UserEvent/{id}', [UserEventController::class, 'show'])->name('user-event.show');


//AdminEvent
Route::get('/registrations', [AdminEventController::class, 'viewRegistrations'])->name('registrations');
// Ruta para mostrar todos los usuarios registrados (solo para el rol Admin)
// Ver todos los usuarios
Route::get('/users', [AdminEventController::class, 'viewAllUsers'])->name('admin.users.index')->middleware(RoleMiddleware::class);
// Suspender o reactivar un usuario
Route::patch('/users/{user}/suspend', [AdminEventController::class, 'suspendUser'])->name('admin.users.suspend');
// Mostrar el formulario para editar un usuario
Route::get('/users/{user}/edit', [AdminEventController::class, 'editUser'])->name('admin.users.edit');
// Actualizar un usuario (ruta corregida para usar PATCH en lugar de POST)
Route::patch('/users/{user}', [AdminEventController::class, 'updateUser'])->name('admin.users.update');
// Eliminar un usuario
Route::delete('/users/{user}', [AdminEventController::class, 'destroyUser'])->name('admin.users.destroy'); */