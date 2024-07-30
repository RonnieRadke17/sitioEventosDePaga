
    document.addEventListener('DOMContentLoaded', function() {
            //muestra el campo de capacidad y lo pone al lado de price
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

            //muestra el mapa del lugar del evento
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
