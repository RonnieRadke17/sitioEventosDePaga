<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite('resources/css/app.css') 
    @yield('head')
    <title>@yield('title')</title>
</head>
<body class="bg-gray-100">
    <nav class="bg-gray-800">
        <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">
            <div class="relative flex items-center justify-between h-16">
                <div class="flex-1 flex items-center justify-start">
                    <div class="flex-shrink-0">
                        <img class="h-8 w-8" src="https://tailwindui.com/img/logos/mark.svg?color=indigo&shade=500" alt="Workflow">
                    </div>
                    <div class="ml-6 flex space-x-4">
                        <a href="#" class="bg-gray-900 text-white px-3 py-2 rounded-md text-sm font-medium">Deportes</a>
                    </div>
                </div>
                <div class="relative">
                    <button type="button" class="bg-gray-800 flex text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-white" id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                        <span class="sr-only">Open user menu</span>
                        <img class="h-8 w-8 rounded-full" src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="">
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Lateral Menu -->
<div class="hidden fixed inset-0 bg-gray-800 bg-opacity-75 z-50" id="lateral-menu-overlay"></div>
<div class="hidden fixed inset-y-0 right-0 w-64 bg-white shadow-lg z-50" id="lateral-menu">
    <div class="py-4 px-6">
        <!-- Profile Section -->
        <div class="w-full bg-gray-100 p-4 rounded-lg flex flex-col items-center">
            <img class="h-16 w-16 rounded-full" src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="User profile picture">
            <span class="mt-2 font-bold">Persona</span>
        </div>
        <!-- Menu Items -->
        <div class="mt-4 border-t border-gray-200 pt-4">
            <a href="#" class="block px-4 py-2 text-sm text-gray-700">Your Profile</a>
            <a href="#" class="block px-4 py-2 text-sm text-gray-700">Settings</a>
            <a href="#" class="block px-4 py-2 text-sm text-gray-700">Sign out</a>
            <div class="mt-4">
                <a href="#" class="text-gray-300 hover:bg-gray-700 hover:text-white block px-3 py-2 rounded-md text-base font-medium">Team</a>
                <a href="#" class="text-gray-300 hover:bg-gray-700 hover:text-white block px-3 py-2 rounded-md text-base font-medium">Projects</a>
                <a href="#" class="text-gray-300 hover:bg-gray-700 hover:text-white block px-3 py-2 rounded-md text-base font-medium">Calendar</a>
            </div>
        </div>
    </div>
</div>
<!--header y nav-->
    <!--un nav tiene que tener icono nombre y icono de user-->
    <!--un nav tiene que tener todas las acciones del profesor-->
   
    @yield('content')

    <footer>plantilla blade que va a contener la barra de navegacion y el menu lateral</footer>

    <script>
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

        document.getElementById('lateral-menu-overlay').addEventListener('click', function() {
            var menu = document.getElementById('lateral-menu');
            var overlay = document.getElementById('lateral-menu-overlay');
            menu.classList.add('hidden');
            overlay.classList.add('hidden');
        });
    </script>
</body>
</html>