
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
