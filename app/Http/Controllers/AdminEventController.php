<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Event;
use Illuminate\Http\Request;
use Carbon\Carbon; // Importa Carbon

class AdminEventController extends Controller
{
    public function viewAllUsers(Request $request)
    {
        // Obtener los valores del formulario de búsqueda
        $name = $request->input('name');
        $paternal = $request->input('paternal');
        $maternal = $request->input('maternal');
        $is_suspended = $request->input('is_suspended');
        $age = $request->input('age');

        // Consultar todos los usuarios con filtros opcionales
        $users = User::when($name, function ($query, $name) {
            return $query->where('name', 'like', "%$name%");
        })
        ->when($paternal, function ($query, $paternal) {
            return $query->where('paternal', 'like', "%$paternal%");
        })
        ->when($maternal, function ($query, $maternal) {
            return $query->where('maternal', 'like', "%$maternal%");
        })
        ->when(isset($is_suspended), function ($query) use ($is_suspended) {
            return $query->where('is_suspended', $is_suspended);
        })
        ->when($age, function ($query, $age) {
            // Calcular la edad comparando la fecha de nacimiento con la fecha actual
            $query->whereRaw('TIMESTAMPDIFF(YEAR, birthdate, CURDATE()) = ?', [$age]);
        })
        ->get();

        return view('Admin.users', compact('users'));
    }

    // Método para suspender o reactivar un usuario
    public function suspendUser(User $user)
    {
        $user->is_suspended = !$user->is_suspended;
        $user->save();

        return redirect()->route('admin.users.index')->with('mensaje', $user->is_suspended ? 'Usuario suspendido.' : 'Usuario reactivado.');
    }

    // Método para editar un usuario (vista de modificación)
    public function editUser(User $user)
    {
        return view('Admin.edit_user', compact('user'));
    }

    // Método para actualizar un usuario (proceso de edición)
    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'paternal' => 'required|string|max:255',
            'maternal' => 'nullable|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'birthdate' => 'required|date',  // Validar que sea una fecha
        ]);

        // Actualizar el usuario, incluyendo la fecha de nacimiento
        $user->update($request->only('name', 'paternal', 'maternal', 'email', 'birthdate'));

        return redirect()->route('admin.users.index')->with('mensaje', 'Usuario modificado exitosamente.');
    }

    // Método para eliminar un usuario
    public function destroyUser(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('mensaje', 'Usuario eliminado.');
    }
    public function viewRegistrations(Request $request)
    {
        // Obtener el ID del evento desde la solicitud
        $eventId = $request->input('event_id');

        // Verificar si se ha proporcionado un ID de evento
        if (!$eventId) {
            return redirect()->back()->with('error', 'No se ha especificado un evento.');
        }

        // Buscar el evento por su ID
        $event = Event::findOrFail($eventId);

        // Obtener los usuarios registrados en este evento
        $registeredUsers = $event->users()->get();

        // Pasar los datos a la vista
        return view('admin.registrations', compact('event', 'registeredUsers')); // Actualizar aquí la referencia a la vista
    }

    // Otro
    
}
