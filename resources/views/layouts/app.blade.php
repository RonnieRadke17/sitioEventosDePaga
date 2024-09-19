<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite('resources/css/app.css') 
    @yield('head')
    <title>@yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://sdk.mercadopago.com/js/v2"></script>
    <style>
        /* Estilos personalizados para el modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 50;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: #FFFFFF;
            padding: 20px;
            width: 100%;
            max-width: 600px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            animation: fadeIn 0.5s;
            border-left: 4px solid #1E3A8A; /* Borde azul */
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .close-button {
            color: #888;
            float: right;
            font-size: 24px;
            cursor: pointer;
        }

        .close-button:hover, .close-button:focus {
            color: #000;
            text-decoration: none;
            cursor: pointer;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-title {
            font-size: 1.5rem;
            font-weight: bold;
            color: #1E40AF; /* Azul */
        }

        .modal-body {
            margin-top: 10px;
            font-size: 1rem;
            color: #333;
        }

        /* Estilos para la barra de navegación */
        .navbar {
            background-color: #1E3A8A; /* Azul oscuro */
        }

        .navbar a {
            color: #FFFFFF;
            padding: 10px 20px;
            text-decoration: none;
        }

        .navbar a:hover {
            background-color: #2563EB; /* Azul más claro */
        }

        /* Estilo para el pie de página */
        footer {
            background-color: #1E3A8A; /* Azul oscuro */
            color: #FFFFFF;
            padding: 15px 0;
        }

        footer a {
            color: #94A3B8; /* Gris claro */
            text-decoration: none;
        }

        footer a:hover {
            color: #FFFFFF;
        }
    </style>
</head>
<body class="bg-gray-100 font-sans antialiased">
   <!-- Barra de Navegación -->
<nav class="bg-blue-900 text-white shadow-lg">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between h-16">
    <!-- Logo y Título con Hipervínculo -->
    <div class="flex items-center space-x-4">
        <img class="h-17 w-16" src="{{ asset('images/Novalogo.png') }}" alt="Workflow">
        <a href="{{ route('home') }}" class="text-xl font-bold text-white hover:text-gray-200">Eventos Deportivos</a>
    </div>

        
                <div class="relative">
                    <!-- Imagen de perfil con ruta actualizada -->
                    @auth
    <button type="button" class="bg-blue-500 hover:bg-blue-400 flex items-center justify-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-white p-1" id="user-menu-button" aria-expanded="false" aria-haspopup="true">
        <span class="sr-only">Open user menu</span>
        <div class="bg-white rounded-full p-1"> <!-- Contenedor con fondo blanco -->
            <img class="h-8 w-8 rounded-full" src="{{ asset('images/user.png') }}" alt="">
        </div>
    </button>
@endauth

                    @guest
                        <div class="flex space-x-4">
                            <a href="{{ route('login') }}" class="bg-white text-blue-600 px-3 py-2 rounded-md text-sm font-medium">Login</a>
                            <a href="{{ route('register') }}" class="bg-white text-blue-600 px-3 py-2 rounded-md text-sm font-medium">Register</a>
                        </div>
                    @endguest
                </div>
            </div>
        </div>
    </nav>

    <!-- Menú Lateral -->
    <div class="hidden fixed inset-0 bg-gray-800 bg-opacity-75 z-40" id="lateral-menu-overlay"></div>
    <div class="hidden fixed inset-y-0 right-0 w-64 bg-white shadow-lg z-50" id="lateral-menu">
        <div class="py-4 px-6">
            <!-- Sección de Perfil en el Menú Lateral -->
            <div class="w-full bg-blue-100 p-4 rounded-lg flex flex-col items-center">
                <!-- Imagen desde resources -->
                <img class="h-16 w-16 rounded-full" src="{{ asset('/images/user.png') }}" alt="User profile picture">
                
                <!-- Mostrar el nombre del usuario autenticado -->
                <span class="mt-2 font-bold text-blue-900">
                    @auth
                        {{ Auth::user()->name }}
                    @endauth
                </span>
            </div>

            <!--     Elementos del Menú -->
            <div class="mt-4 border-t border-gray-200 pt-4">
                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50">Your Profile</a>
                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50">Settings</a>
                <a href="{{ route('logout') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50">Salir</a>
                <div class="mt-4">
                    <a href="#" class="text-gray-600 hover:bg-blue-100 hover:text-blue-700 block px-3 py-2 rounded-md text-base font-medium">Team</a>
                    <a href="#" class="text-gray-600 hover:bg-blue-100 hover:text-blue-700 block px-3 py-2 rounded-md text-base font-medium">Projects</a>
                    <a href="#" class="text-gray-600 hover:bg-blue-100 hover:text-blue-700 block px-3 py-2 rounded-md text-base font-medium">Calendar</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Éxito -->
    @if(session('success'))
    <div id="successModal" class="modal flex">
        <div class="modal-content">
            <div class="modal-header">
                <span class="modal-title">¡Éxito!</span>
                <span class="close-button" id="closeModal">&times;</span>
            </div>
            <div class="modal-body">
                {{ session('success') }}
            </div>
        </div>
    </div>       
    @endif

    @yield('content')
    
<a href="{{ route('event.index') }}" class="btn btn-primary rounded-circle position-fixed" style="bottom: 20px; left: 20px; width: 50px; height: 50px; display: flex; justify-content: center; align-items: center;">
    <i class="fas fa-pencil-alt"></i>
</a>

<!-- Incluir Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- Footer -->
    <footer class="bg-blue-900 text-white text-center py-4 mt-12">
        <p>&copy; 2024 Eventos Deportivos. Todos los derechos reservados.</p>
        <div class="mt-2">
            <a href="#" class="text-gray-300 hover:text-blue-400 mx-2">Política de Privacidad</a>
            <a href="#" class="text-gray-300 hover:text-blue-400 mx-2">Términos y Condiciones</a>
        </div>
    </footer>

    <script>
        // Función para mostrar el modal de éxito
        function showModal() {
            var modal = document.getElementById('successModal');
            if (modal) {
                modal.style.display = 'flex';
            }
        }

        // Función para cerrar el modal
        function closeModal() {
            var modal = document.getElementById('successModal');
            if (modal) {
                modal.style.display = 'none';
            }
        }

        // Evento para mostrar el modal cuando se cargue la página
        document.addEventListener('DOMContentLoaded', function() {
            showModal();

            // Agregar evento al botón de cerrar
            var closeButton = document.getElementById('closeModal');
            if (closeButton) {
                closeButton.addEventListener('click', closeModal);
            }

            // Cerrar el modal al hacer clic fuera del contenido
            window.addEventListener('click', function(e) {
                var modal = document.getElementById('successModal');
                if (e.target == modal) {
                    closeModal();
                }
            });

            // Mostrar/Ocultar el menú lateral
            document.getElementById('user-menu-button').addEventListener('click', function() {
                var menu = document.getElementById('lateral-menu');
                var overlay = document.getElementById('lateral-menu-overlay');
                if (menu.classList.contains('hidden')) {
                    menu.classList.remove('hidden');
                    overlay.classList.remove('hidden');
                } else {
                    menu.classList.add('hidden');
                    overlay.classList.add('hidden');
                }
            });

            // Cerrar el menú lateral al hacer clic en el overlay
            document.getElementById('lateral-menu-overlay').addEventListener('click', function() {
                var menu = document.getElementById('lateral-menu');
                var overlay = document.getElementById('lateral-menu-overlay');
                menu.classList.add('hidden');
                overlay.classList.add('hidden');
            });
        });
    </script>
</body>
</html>
