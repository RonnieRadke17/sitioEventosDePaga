<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <div class="container">
    <h1>Detalle del Deporte</h1>
    <div id="sport-detail">
        <p>Cargando datos...</p>
    </div>

    
    </div>

    <script>
document.addEventListener('DOMContentLoaded', () => {
    const sportId = @json($sportId);
    console.log('Usando sportId:', sportId);

    fetch('/api/sports/' + sportId)
        .then(res => {
            if (!res.ok) throw new Error('Error HTTP');
            return res.json();
        })
        .then(data => {
            console.log('Respuesta:', data);
            const container = document.getElementById('sport-detail');
            if (data.data) {
                container.innerHTML = `
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">ID: ${data.data.id}</h5>
                            <p class="card-text">Nombre: ${data.data.name}</p>
                        </div>
                    </div>
                `;
            } else {
                container.innerHTML = `<p class="text-danger">${data.error || 'Deporte no encontrado.'}</p>`;
            }
        })
        .catch(err => {
            console.error('Error en fetch:', err);
            document.getElementById('sport-detail').innerHTML = '<p class="text-danger">Error al cargar el deporte.</p>';
        });
});
</script>

</body>
</html>



