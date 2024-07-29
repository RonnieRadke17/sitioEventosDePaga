@extends('layouts.app')
@section('head')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    /**
     * @license
     * Copyright 2024 Google LLC. All Rights Reserved.
     * SPDX-License-Identifier: Apache-2.0
     */
    html, body {
        height: 100%;
        margin: 0;
        padding: 0;
    }

    gmp-map:not(:defined) {
        display: none;
    }

    #title {
        color: #fff;
        background-color: #4d90fe;
        font-size: 25px;
        font-weight: 500;
        padding: 6px 12px;
    }

    #infowindow-content {
        display: none;
    }

    .pac-card {
        background-color: #fff;
        border-radius: 2px;
        box-shadow: 0 1px 4px -1px rgba(0, 0, 0, 0.3);
        margin: 10px;
        font: 400 18px Roboto, Arial, sans-serif;
        overflow: hidden;
    }

    .pac-controls {
        display: inline-block;
        padding: 5px 11px;
    }

    .pac-controls label {
        font-size: 13px;
        font-weight: 300;
    }

    #place-picker {
        box-sizing: border-box;
        width: 100%;
        padding: 0.5rem 1rem 1rem;
    }

    /* Estilo para el contenedor del mapa */
    #map-container {
        height: 400px; /* Ajusta la altura según tus necesidades */
        margin-top: 20px;
    }

    /* Estilo para el mapa */
    gmp-map {
        height: 100%;
        width: 100%;
    }
</style>

<script>
    /**
     * @license
     * Copyright 2024 Google LLC. All Rights Reserved.
     * SPDX-License-Identifier: Apache-2.0
     */
    async function init() {
      await customElements.whenDefined('gmp-map');

      const map = document.querySelector("gmp-map");
      const marker = document.getElementById("marker");
      const strictBoundsInputElement = document.getElementById("use-strict-bounds");
      const placePicker = document.getElementById("place-picker");
      const infowindowContent = document.getElementById("infowindow-content");
      const infowindow = new google.maps.InfoWindow();

      map.innerMap.setOptions({mapTypeControl: false});
      infowindow.setContent(infowindowContent);

      placePicker.addEventListener('gmpx-placechange', () => {
        const place = placePicker.value;

        if (!place.location) {
          window.alert(
            "No details available for input: '" + place.name + "'"
          );
          infowindow.close();
          marker.position = null;
          return;
        }

        if (place.viewport) {
          map.innerMap.fitBounds(place.viewport);
        } else {
          map.center = place.location;
          map.zoom = 17;
        }

        marker.position = place.location;
        infowindowContent.children["place-name"].textContent = place.displayName;
        infowindowContent.children["place-address"].textContent = place.formattedAddress;
        infowindow.open(map.innerMap, marker);
      });

      // Sets a listener on a radio button to change the filter type on the place picker
      function setupClickListener(id, type) {
        const radioButton = document.getElementById(id);
        radioButton.addEventListener("click", () => {
          placePicker.type = type;
        });
      }
      setupClickListener("changetype-all", "");
      setupClickListener("changetype-address", "address");
      setupClickListener("changetype-establishment", "establishment");
      setupClickListener("changetype-geocode", "geocode");
      setupClickListener("changetype-cities", "(cities)");
      setupClickListener("changetype-regions", "(regions)");

      strictBoundsInputElement.addEventListener("change", () => {
        placePicker.strictBounds = strictBoundsInputElement.checked;
      });
    }

    document.addEventListener('DOMContentLoaded', init);
</script>

<script type="module" src="https://unpkg.com/@googlemaps/extended-component-library@0.6"></script>

@endsection
@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="bg-white p-8 rounded-lg shadow-lg w-full md:w-2/3 lg:w-1/2">
        <!-- Step indicators -->
        <div class="mb-6 flex justify-between items-center">
            <div class="w-1/3 text-center">
                <span class="block w-8 h-8 bg-blue-500 text-white rounded-full mx-auto">1</span>
                <span class="text-gray-700 text-sm">Event Details</span>
            </div>
            <div class="w-1/3 text-center">
                <span class="block w-8 h-8 bg-gray-300 text-white rounded-full mx-auto">2</span>
                <span class="text-gray-700 text-sm">Activities</span>
            </div>
        </div>

        <!-- Formulario -->
        <form action="{{ route('event.store') }}" method="post" id="multi-step-form">
            @csrf
            @include('event.form',['mode'=>'Registrar'])
        
        </form>
    </div>
</div>

<!-- Flatpickr JS -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

{{-- script del calendario --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        flatpickr('#event_date', {
            enableTime: true,
            dateFormat: "Y-m-d H:i",
        });
        flatpickr('#kit_delivery', {
            enableTime: true,
            dateFormat: "Y-m-d H:i",
        });
        flatpickr('#registration_deadline', {
            enableTime: true,
            dateFormat: "Y-m-d H:i",
        });
    });
</script>

{{-- estilos de los checkbox --}}
<style>
        /* Estilo para el contenedor del checkbox */
    .checkbox-container, .gender-container {
        display: inline-flex;
        align-items: center;
        cursor: pointer;
        position: relative;
        padding: 0;
        margin: 0;
    }

    /* Escondemos el checkbox original */
    .checkbox-container input, .hidden-gender-checkbox {
        opacity: 0;
        position: absolute;
        cursor: pointer;
        height: 0;
        width: 0;
    }

    /* El círculo del checkbox */
    .checkmark, .gender-checkmark {
        height: 20px;
        width: 20px;
        border-radius: 50%;
        background-color: red;
        display: inline-block;
        border: 2px solid #ccc;
        transition: background-color 0.3s;
    }

    /* Estilo cuando el checkbox está seleccionado */
    .checkbox-container input:checked ~ .checkmark {
        background-color: green;
        border-color: green;
    }

    /* Estilo para los checkboxes de género */
    .gender-checkmark {
        margin-right: 5px;
    }

    .gender-container {
        display: flex;
        align-items: center;
        gap: 10px; /* Espacio entre los checkboxes de género */
    }

    /* Distribución de los géneros */
    .gender-container:nth-child(1) {
        margin-right: auto; /* Género a la izquierda */
    }

    .gender-container:nth-child(2) {
        margin: 0 auto; /* Género en el centro */
    }

    .gender-container:nth-child(3) {
        margin-left: auto; /* Género a la derecha */
    }

    /* Estilo para el checkbox de género */
    .gender-container .gender-checkmark {
        background-color: red;
        border-color: #ccc;
    }

    .gender-container input:checked + .gender-checkmark {
        background-color: green;
        border-color: green;
    }

</style>

{{-- script que muestra el contenido el en form --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle capacity field visibility based on limited capacity selection
        document.getElementById('is_limited_capacity').addEventListener('change', function() {
            const capacityField = document.getElementById('capacity-field');
            if (this.value == '1') {
                capacityField.classList.remove('hidden');
            } else {
                capacityField.classList.add('hidden');
            }
        });
    
        // Mostrar/ocultar actividades
        document.getElementById('is_with_activities').addEventListener('change', function() {
            const activityTable = document.getElementById('activity_table');
            if (this.value == '1') {
                activityTable.classList.remove('hidden');
            } else {
                activityTable.classList.add('hidden');
            }
        });
    
        // Cambiar de paso
       // Mostrar/ocultar pasos
    document.getElementById('to-step-2').addEventListener('click', function() {
        document.getElementById('step-1').classList.add('hidden');
        document.getElementById('step-2').classList.remove('hidden');
        document.getElementById('step-3').classList.add('hidden'); // Asegúrate de ocultar el paso 3
    });

    document.getElementById('to-step-1').addEventListener('click', function() {
        document.getElementById('step-1').classList.remove('hidden');
        document.getElementById('step-2').classList.add('hidden');
        document.getElementById('step-3').classList.add('hidden'); // Asegúrate de ocultar el paso 3
    });

    document.getElementById('to-step-3').addEventListener('click', function() {
        document.getElementById('step-1').classList.add('hidden');
        document.getElementById('step-2').classList.add('hidden');
        document.getElementById('step-3').classList.remove('hidden');
    });

    document.getElementById('return-step2').addEventListener('click', function() {
        document.getElementById('step-1').classList.add('hidden');
        document.getElementById('step-2').classList.remove('hidden');
        document.getElementById('step-3').classList.add('hidden'); // Asegúrate de ocultar el paso 3
    });

    document.getElementById('return-step3').addEventListener('click', function() {
        document.getElementById('step-1').classList.add('hidden');
        document.getElementById('step-2').classList.add('hidden');
        document.getElementById('step-3').classList.remove('hidden'); // Asegúrate de ocultar el paso 3
        document.getElementById('step-4').classList.add('hidden');
    });

    document.getElementById('to-step-4').addEventListener('click', function() {
        document.getElementById('step-1').classList.add('hidden');
        document.getElementById('step-2').classList.add('hidden');
        document.getElementById('step-3').classList.add('hidden');
        document.getElementById('step-4').classList.remove('hidden');
    });

    
        // Mostrar/ocultar detalles de la actividad
        document.querySelectorAll('.activity-row').forEach(row => {
            row.addEventListener('click', function() {
                const activityId = this.dataset.activityId;
                const detailsRow = document.getElementById(`activity-${activityId}-details`);
                detailsRow.classList.toggle('hidden');
            });
        });
    
        // Manejar clic en el checkbox de género cambiar por el span o otra
        document.querySelectorAll('.gender-container input[type="checkbox"]').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const activityId = this.name.match(/genders\[(\d+)\]/)[1];
                const gender = this.value;
                const subsDivs = document.querySelectorAll(`#activity-${activityId}-gender-M-subs, #activity-${activityId}-gender-F-subs, #activity-${activityId}-gender-Mix-subs`);
    
                // Ocultar todas las sub-actividades de género
                subsDivs.forEach(subsDiv => {
                    subsDiv.classList.add('hidden');
                });
    
                // Mostrar solo las sub-actividades del género seleccionado
                if (this.checked) {
                    document.querySelector(`#activity-${activityId}-gender-${gender}-subs`).classList.remove('hidden');
                }
            });
        });
    });
</script>
    
{{-- script que muestra el mapa del lugar del evento --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const placeSelect = document.getElementById('place_id');
        const mapContainer = document.getElementById('map-container');
        const mapElement = document.getElementById('map');
        
        placeSelect.addEventListener('change', function() {
            if (this.value === 'Otro') {
                mapContainer.classList.remove('hidden');
                // Inicializa el mapa si es necesario
                if (!mapElement.dataset.initialized) {
                    mapElement.dataset.initialized = true;
                }
            } else {
                mapContainer.classList.add('hidden');
            }
        });
    });
</script>
    

@endsection
