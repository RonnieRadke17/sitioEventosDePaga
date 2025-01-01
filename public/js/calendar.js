window.onload = function() {
    // Usamos setTimeout para darle tiempo a Flatpickr para renderizar el calendario
    setTimeout(function() {
        const component = document.querySelector('.flatpickr-calendar');
        
        
        if (component) {
            component.classList.add('dark:bg-gray-600');
            /* const elements = component.querySelectorAll('*'); // Selecciona todos los elementos dentro del contenedor
    
            elements.forEach((el) => {
                el.classList.add('dark:text-white'); // Agrega la clase dark:text-white a cada uno
            }); */
        }

    }, 100);  // Ajusta el tiempo según lo necesario
}

    
    
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
        flatpickr('#birthdate', {
            enableTime: false,
            dateFormat: "Y-m-d",
        });
    });

   
    
    