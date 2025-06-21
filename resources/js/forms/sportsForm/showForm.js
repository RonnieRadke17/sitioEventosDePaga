    window.onload = function() {
        /* codigo para mostrar el modal abierto si hay error en el back */
        let nameErrorMessage = document.getElementById('nameError')?.innerText.trim();
        let categoryErrorMessage = document.getElementById('categoryError')?.innerText.trim();
            if (nameErrorMessage || categoryErrorMessage) {
                // Mostrar el modal y solucion temporal debido a que no muestra los colores adecuados hasta cierto punto
                const modal = document.getElementById('form-modal');
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }

        const createButton = document.getElementById('create');
        //const editButton = document.getElementById('edit');
        const form = document.getElementById('formSport');
    
        //verificar si el botón y el formulario existen
        if (createButton && form) {
            createButton.addEventListener('click', function() {
                const methodInput = form.querySelector('input[name="_method"]');
                if (methodInput) {
                    methodInput.remove();
                }

                form.action = "{{ route('sports.store') }}";
                console.log(form.action);
                
                // Limpiar los campos del formulario
                document.getElementById('name').value = '';
                document.getElementById('category_id').value = '';
                document.getElementById('nameError').innerText = '';
                document.getElementById('categoryError').innerText = '';

                form.reset();
            });
        }
        /* codigo para  el btn de editar*/
        document.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', function () {
                const id = this.dataset.id;
                const name = this.dataset.name;
                const category = this.dataset.category;

                let methodInput = form.querySelector('input[name="_method"]');
                if (!methodInput) {
                    methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    form.appendChild(methodInput);
                }
                methodInput.value = 'PATCH';

                form.querySelector('input[name="name"]').value = name;
                form.querySelector('select[name="category_id"]').value = category;

                form.action = "{{ url('sports') }}/" + id;
                console.log(form.action);
            });
        });
    };
