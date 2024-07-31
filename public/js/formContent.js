
    document.addEventListener('DOMContentLoaded', function() {
            //muestra el campo de capacidad y lo pone al lado de price
            document.getElementById('is_limited_capacity').addEventListener('change', function() {
                const capacityField = document.getElementById('capacity-field');
                const priceContent = document.getElementById('price-content');
                const divContent = document.getElementById('div-content');
                if
                 (this.value == '1') {
                    /* 
                        si se muestra capacidad entonces  debe ser w-1/2 pr-2 ya 
                        capacidad debe quitarle el mb-4 hidden 
                        y a price-content quitarle el mb-4 y ponerle el w-1/2 pl-2 ya 
                        como a su vez al divContent se le debe poner flex mb-4 ya  
                    */

                    divContent.classList.add('flex','mb-4');

                    capacityField.classList.remove('mb-4','hidden');
                    capacityField.classList.add('w-1/2', 'pr-2');

                    priceContent.classList.remove('mb-4');
                    priceContent.classList.add('w-1/2','pl-2');
                } else {
                    //no se muestra
                    divContent.classList.remove('flex','mb-4');

                    capacityField.classList.remove('w-1/2', 'pr-2');
                    capacityField.classList.add('mb-4','hidden');

                    priceContent.classList.remove('w-1/2','pl-2');
                    priceContent.classList.add('mb-4');
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
