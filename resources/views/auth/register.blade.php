@extends('layouts.app') 
@section('title','Registro')
@section('head')
<script src="{{ asset('js/calendar.js') }}"></script>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

@endsection
@section('content')

<div class="min-h-screen flex items-center justify-center ">
        <!-- Mostrar errores de validación -->
        {{-- @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif --}}

        <!-- Mostrar mensaje de error de sesión -->
       {{--  @if (session()->get('error'))
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
                {{ session()->get('error') }}
            </div>
        @endif --}}

        <!-- Formulario -->
        <form method="POST" action="{{ route('process-register') }}" class="max-w-md mx-auto p-6 bg-white shadow-lg rounded-lg border border-gray-200 dark:text-white dark:bg-gray-600 dark:border-gray-600">
            @csrf

            <!-- Título -->
            <h2 class="text-3xl font-bold text-center text-orange-600 mb-2">Registro de Usuario</h2>
            <p class="text-center text-gray-600 mb-6">Completa el formulario para crear tu cuenta</p>

            <!-- Contenedor de campos en dos columnas -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <!-- Nombre -->
                <x-forms.input-text class="col-span-1 md:col-span-2" name="name" description="Nombre"></x-forms.input-text>
                
                {{-- <div class="col-span-1 md:col-span-2">
                    <div class="relative">
                        <input type="text" name="name" id="name" autocomplete="off" value="{{ old('name') }}" class="block rounded-t-lg px-2.5 pb-2.5 pt-5 w-full text-sm text-gray-900 bg-gray-50 dark:bg-gray-700 border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " />
                        <label for="name" class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-4 z-10 origin-[0] start-2.5 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto">Nombres</label>
                    </div>
                    @error('name')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div> --}}

                <!-- Apellido Paterno -->
                <x-forms.input-text name="paternal" description="Apellido Paterno"></x-forms.input-text>
                
                {{-- <div>
                    <div class="relative">
                        <input type="text" name="paternal" id="paternal" autocomplete="off" value="{{ old('paternal') }}" class="block rounded-t-lg px-2.5 pb-2.5 pt-5 w-full text-sm text-gray-900 bg-gray-50 dark:bg-gray-700 border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " />
                        <label for="paternal" class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-4 z-10 origin-[0] start-2.5 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto">Apellido Paterno</label>
                    </div>
                    @error('paternal')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div> --}}

                <!-- Apellido Materno -->
                <x-forms.input-text name="maternal" description="Apellido Materno"></x-forms.input-text>
                {{-- <div>
                    <div class="relative">
                        <input type="text" name="maternal" id="maternal" autocomplete="off" value="{{ old('maternal') }}" class="block rounded-t-lg px-2.5 pb-2.5 pt-5 w-full text-sm text-gray-900 bg-gray-50 dark:bg-gray-700 border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " />
                        <label for="maternal" class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-4 z-10 origin-[0] start-2.5 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto">Apellido Materno</label>
                    </div>
                    @error('maternal')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div> --}}

                <!-- Fecha de nacimiento -->
                <div class="md:col-span-2">
                    <div class="relative">
                    <input type="text" name="birthdate" id="birthdate" class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:text-gray-400 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="🗓️Fecha de nacimiento" value="{{ old('birthdate') }}">
                    <!-- Mensaje de error -->
                    @error('birthdate')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                    </div>
                </div>

                <!-- Género -->
                <div class="col-span-1 md:col-span-2">
                    <label class="block text-gray-800 text-sm font-medium mb-1 dark:text-gray-400">Género</label>
                    <div class="flex items-center">
                        <input type="radio" name="gender" id="male" value="M" class="mr-2 text-orange-600 focus:ring-orange-500 border-gray-300 rounded" {{ old('gender') == 'M' ? 'checked' : '' }}>
                        <label for="male" class="mr-4">Masculino</label>
                        <input type="radio" name="gender" id="female" value="F" class="mr-2 text-orange-600 focus:ring-orange-500 border-gray-300 rounded" {{ old('gender') == 'F' ? 'checked' : '' }}>
                        <label for="female">Femenino</label>
                    </div>
                    @error('gender')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Correo Electrónico -->
                <x-forms.input-text class="col-span-1 md:col-span-2" name="email" description="Correo"></x-forms.input-text>
                {{-- <div class="col-span-1 md:col-span-2">
                    <div class="relative">
                        <input type="email" name="email" id="email" autocomplete="off" value="{{ old('email') }}" class="block rounded-t-lg px-2.5 pb-2.5 pt-5 w-full text-sm text-gray-900 bg-gray-50 dark:bg-gray-700 border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " />
                        <label for="email" class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-4 z-10 origin-[0] start-2.5 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto">Correo</label>
                    </div>
                    @error('email')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div> --}}

                <!-- Contraseña -->
                <div class="relative">

                    <div class="relative">
                        <input type="password" name="password" id="password" autocomplete="off" class="block rounded-t-lg px-2.5 pb-2.5 pt-5 w-full text-sm text-gray-900 bg-gray-50 dark:bg-gray-700 border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " />
                        <label for="password" class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-4 z-10 origin-[0] start-2.5 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto">Contraseña</label>
                    </div>
                    @error('password')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Confirmar Contraseña -->
                <div class="relative">
                    <div class="relative">
                        <input type="password" name="password_confirmation" id="password_confirmation" autocomplete="off" class="block rounded-t-lg px-2.5 pb-2.5 pt-5 w-full text-sm text-gray-900 bg-gray-50 dark:bg-gray-700 border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " />
                        <label for="password_confirmation" class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-4 z-10 origin-[0] start-2.5 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto">Confirmar contraseña</label>
                    </div>
                </div>
            </div>

            <!-- Términos y condiciones -->
            <div class="flex items-center mt-4 mb-6">
                <p for="terms" class="ml-2 text-sm font-medium text-gray-700  dark:text-white">
                    Al hacer clic en "Registrarte", aceptas nuestros
                    <a href="#" id="terms-link" class="text-orange-600 hover:text-orange-500">términos y condiciones</a>.
                </p>
                @error('terms')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
            
            <!-- Botón de Registro -->
            <x-forms.submit-button value='Registrarme'>Registrarme</x-forms.submit-button>
        </form>
    
</div>





  {{-- modal --}}
<div id="static-modal" data-modal-backdrop="static" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-2xl max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                    Terminos y Condiciones
                </h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="static-modal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <div class="p-4 md:p-5 space-y-4 overflow-y-auto max-h-[400px] scrollbar-thin scrollbar-thumb-gray-400 scrollbar-track-gray-200 dark:scrollbar-thumb-gray-600 dark:scrollbar-track-gray-800">
                <p class="text-base leading-relaxed text-gray-500 dark:text-gray-400">
                    With less than a month to go before the European Union enacts new consumer privacy laws for its citizens, companies around the world are updating their terms of service agreements to comply.
                </p>
                <p class="text-base leading-relaxed text-gray-500 dark:text-gray-400">
                    The European Union’s General Data Protection Regulation (G.D.P.R.) goes into effect on May 25 and is meant to ensure a common set of data rights in the European Union. It requires organizations to notify users as soon as possible of high-risk data breaches that could personally affect them.
                </p>
                <!-- Agregar más contenido aquí para probar el scroll -->
                <p class="text-base leading-relaxed text-gray-500 dark:text-gray-400">
                    The European Union’s General Data Protection Regulation (G.D.P.R.) goes into effect on May 25 and is meant to ensure a common set of data rights in the European Union. It requires organizations to notify users as soon as possible of high-risk data breaches that could personally affect them.
                </p>
                <p class="text-base leading-relaxed text-gray-500 dark:text-gray-400">
                    The European Union’s General Data Protection Regulation (G.D.P.R.) goes into effect on May 25 and is meant to ensure a common set of data rights in the European Union. It requires organizations to notify users as soon as possible of high-risk data breaches that could personally affect them.
                </p>
                <p class="text-base leading-relaxed text-gray-500 dark:text-gray-400">
                    The European Union’s General Data Protection Regulation (G.D.P.R.) goes into effect on May 25 and is meant to ensure a common set of data rights in the European Union. It requires organizations to notify users as soon as possible of high-risk data breaches that could personally affect them.
                </p>
                <p class="text-base leading-relaxed text-gray-500 dark:text-gray-400">
                    The European Union’s General Data Protection Regulation (G.D.P.R.) goes into effect on May 25 and is meant to ensure a common set of data rights in the European Union. It requires organizations to notify users as soon as possible of high-risk data breaches that could personally affect them.
                </p>
                <p class="text-base leading-relaxed text-gray-500 dark:text-gray-400">
                    The European Union’s General Data Protection Regulation (G.D.P.R.) goes into effect on May 25 and is meant to ensure a common set of data rights in the European Union. It requires organizations to notify users as soon as possible of high-risk data breaches that could personally affect them.
                </p>

            </div>
            <!-- Modal footer -->
            <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
            </div>
        </div>
    </div>
</div>

  


{{-- script del modal --}}
<script>
    document.addEventListener('DOMContentLoaded', () => {
    const modalToggleButtons = document.querySelectorAll('[data-modal-toggle]');
    const modalHideButtons = document.querySelectorAll('[data-modal-hide]');
    const termsLink = document.getElementById('terms-link');
    const modal = document.getElementById('static-modal');
    const modalContent = modal.querySelector('.relative.bg-white');

    // Función para mostrar el modal
    const showModal = () => {
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex'); // Centrar el modal
            document.body.classList.add('overflow-hidden'); // Bloquear scroll
        }
    };

    // Función para ocultar el modal
    const hideModal = () => {
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.classList.remove('overflow-hidden'); // Habilitar scroll
        }
    };

    // Mostrar el modal al hacer clic en "términos y condiciones"
    if (termsLink) {
        termsLink.addEventListener('click', (e) => {
            e.preventDefault(); // Evitar comportamiento por defecto del enlace
            showModal();
        });
    }

    // Mostrar modal desde botones con data-modal-toggle
    modalToggleButtons.forEach(button => {
        button.addEventListener('click', showModal);
    });

    // Ocultar modal desde botones con data-modal-hide
    modalHideButtons.forEach(button => {
        button.addEventListener('click', hideModal);
    });

    // Ocultar modal al hacer clic fuera del contenido
    modal.addEventListener('click', (e) => {
        if (!modalContent.contains(e.target)) {
            hideModal();
        }
    });
});

</script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
@endsection
