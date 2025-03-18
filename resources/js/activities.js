document.addEventListener("DOMContentLoaded", function () {
    const selectElement = document.getElementById("is_with_activities");
    const activitiesList = document.getElementById("list-activities");

    function toggleActivitiesList() {
        if (selectElement.value === "1") {
            activitiesList.style.display = "grid"; // Mostrar la lista
        } else {
            activitiesList.style.display = "none"; // Ocultar la lista
        }
    }

    // Ejecutar la función al cargar la página para establecer el estado inicial
    toggleActivitiesList();

    // Agregar evento para detectar cambios en el select
    selectElement.addEventListener("change", toggleActivitiesList);
});
