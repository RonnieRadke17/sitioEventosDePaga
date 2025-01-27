window.onload = function() {
    //obtener los elementos del html
    //actividades
    const isWithActivities = document.getElementById('is_with_activities');
    const activityTable = document.getElementById('activity_table');
    //mostrar el campo de capacidad o no
    /* const isLimitedCapacity = document.getElementById('is_limited_capacity');
    const capacityField = document.getElementById('capacity-field');
    const priceContent = document.getElementById('price-content');
    const divContent = document.getElementById('div-content');
    //mostrar el campo de el mapa o la opcion seleccionada del map
    //muestra el mapa del lugar del evento
    const placeId = document.getElementById('place_id');
    const mapContainer = document.getElementById('map-container'); */
    
    // Mostrar/ocultar mapa basado en la selección del lugar
    /* placeId.addEventListener('change', function() {
        if (this.value === 'Otro') {
            mapContainer.classList.remove('hidden');
        } else {
            mapContainer.classList.add('hidden');
        }
    });
 */
    // Función para actualizar la visibilidad de la tabla de actividades
    function updateActivityTableVisibility() {
        if (isWithActivities.value === '1') {
            activityTable.classList.remove('hidden');
        } else {
            activityTable.classList.add('hidden');
        }
    }

    // Función para actualizar el estado del diseño de capacidad y precio
    /* function updateCapacityAndPriceFields() {
        if (isLimitedCapacity.value === '1') {
            divContent.classList.add('flex', 'mb-4');

            capacityField.classList.remove('mb-4', 'hidden');
            capacityField.classList.add('w-1/2', 'pr-2');

            priceContent.classList.remove('mb-4');
            priceContent.classList.add('w-1/2', 'pl-2');
        } else {
            divContent.classList.remove('flex', 'mb-4');

            capacityField.classList.remove('w-1/2', 'pr-2');
            capacityField.classList.add('mb-4', 'hidden');

            priceContent.classList.remove('w-1/2', 'pl-2');
            priceContent.classList.add('mb-4');
        }
    } */

    // Configurar el event listener para el cambio en el select de actividades
    isWithActivities.addEventListener('change', updateActivityTableVisibility);

    // Configurar el event listener para el cambio en el select de capacidad
    /* isLimitedCapacity.addEventListener('change', updateCapacityAndPriceFields); */

    // Actualizar la visibilidad de la tabla y el estado del diseño al cargar la página
    updateActivityTableVisibility();
    /* updateCapacityAndPriceFields(); */
};

    
    
    