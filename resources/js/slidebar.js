    document.addEventListener('DOMContentLoaded', () => {
            const drawerToggle = document.getElementById('drawer-toggle');
            const drawer = document.getElementById('drawer-navigation');
            const closeDrawer = document.getElementById('drawer-close');
        
            // Mostrar el menú
            drawerToggle.addEventListener('click', () => {
                drawer.classList.remove('translate-x-full');
            });
        
            // Ocultar el menú al hacer clic en el botón de cierre
            closeDrawer.addEventListener('click', () => {
                drawer.classList.add('translate-x-full');
            });
        
            // Ocultar el menú al hacer clic fuera del mismo
            document.addEventListener('click', (event) => {
                if (!drawer.contains(event.target) && !drawerToggle.contains(event.target)) {
                    drawer.classList.add('translate-x-full');
                }
            });
    });
    