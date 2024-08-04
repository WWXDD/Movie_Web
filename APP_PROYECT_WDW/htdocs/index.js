// Obtén todas las tarjetas de películas
var peliculas = document.querySelectorAll('.pelicula');

// Añade un evento de clic a cada tarjeta de película
for (var i = 0; i < peliculas.length; i++) {
    peliculas[i].addEventListener('click', function() {
        // Muestra un mensaje de alerta con el título de la película
        var titulo = this.querySelector('h2').innerText;
        alert('Has hecho clic en ' + titulo);
    });
}
