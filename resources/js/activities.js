window.onload = function() {
    //obtener los elementos del html
    //actividades
    const isWithActivities = document.getElementById('is_with_activities');
    const activityTable = document.getElementById('activity_table');
    
    // Función para actualizar la visibilidad de la tabla de actividades
    function updateActivityTableVisibility() {
        if (isWithActivities.value === '1') {
            activityTable.classList.remove('hidden');
        } else {
            activityTable.classList.add('hidden');
        }
    }
    // Configurar el event listener para el cambio en el select de actividades
    isWithActivities.addEventListener('change', updateActivityTableVisibility);
    // Actualizar la visibilidad de la tabla y el estado del diseño al cargar la página
    updateActivityTableVisibility();
};

    
    
    