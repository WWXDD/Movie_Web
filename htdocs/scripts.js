// Función para calcular la puntuación ponderada
function calcularPuntuacionPonderada(totalPeliculas, puntuacionMediaTodas, numeroPuntuacionesPelicula, puntuacionPelicula) {
    return ((totalPeliculas * puntuacionMediaTodas) + (numeroPuntuacionesPelicula * puntuacionPelicula)) / (totalPeliculas + numeroPuntuacionesPelicula);
}

// Ejemplo de uso:
const totalPeliculas = 1000; // Reemplaza este valor con el número total de películas
const puntuacionMediaTodas = 3.5; // Reemplaza este valor con la puntuación media de todas las películas
const numeroPuntuacionesPelicula = 356; // Reemplaza este valor con el número de puntuaciones de la película
const puntuacionPelicula = 3.8; // Reemplaza este valor con la puntuación media de la película

const puntuacionPonderada = calcularPuntuacionPonderada(totalPeliculas, puntuacionMediaTodas, numeroPuntuacionesPelicula, puntuacionPelicula);
console.log('Puntuación Ponderada:', puntuacionPonderada);

// Actualizar la interfaz de usuario con la puntuación ponderada
const puntuacionElement = document.querySelector('.puntuacion');
puntuacionElement.textContent = `Puntuación Ponderada: ${puntuacionPonderada.toFixed(2)}`;
