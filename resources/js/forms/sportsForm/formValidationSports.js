const form = document.getElementById('formSport');
const inputs = document.querySelectorAll('#formSport input');

const expressions = {
	name: /^[a-zA-ZÀ-ÿ\s]{1,40}$/, // Letras y espacios, pueden llevar acentos.
}

const campos = {
	name: false,
}

const validateForm = (e) => {
    console.log(e.target.name);
    switch (e.target.name) {
        case "name":
            if (expressions.name.test(e.target.value)) {//falta el min y max de caracteres de el vaue del input 
                document.getElementById('nameError').innerText = '';
                campos.name = true;
            } else {
                document.getElementById('nameError').innerText = 'El name solo puede contener letras y espacios.';
                campos.name = false;
            }
            break;
        case "category_id":
            if (e.target.value !== '') {
                document.getElementById('categoryError').innerText = '';
            } else {
                document.getElementById('categoryError').innerText = 'Debe seleccionar una categoría.';
            }
            break;
    }
}

inputs.forEach((input) => {
    input.addEventListener('keyup',validateForm);
    input.addEventListener('blur', validateForm);
});