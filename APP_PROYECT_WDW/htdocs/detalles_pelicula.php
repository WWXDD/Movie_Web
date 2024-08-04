<?php
session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Información de Película</title>
    <link rel="stylesheet" href="detalles_pelicula.css">
</head>
<body>
<header>
    <h1>Detalles</h1>

    <?php
    if (isset($_SESSION['name'])) {
        echo '<div class="welcome-msg">¡Bienvenido, ' . htmlspecialchars($_SESSION['name']) . '!</div>';
        echo '<div class="profile-button"><a href="perfil.php">Perfil</a></div>';
        echo '<div class="logout-button"><a href="logout.php">Cerrar sesión</a></div>';
    } else {
        echo '
        <div class="login-form">
            <h2>Iniciar sesión        :         </h2>
            <form action="login.php" method="post">
                <label for="username">Usuario:</label>
                <input type="text" name="username" required>
                <label for="password">Contraseña:</label>
                <input type="password" name="password" required>
                <input type="submit" value="Iniciar sesión">
            </form>
        </div>
        <div class="register-button"><a href="signup.php">Registrarse</a></div>';
    }
    ?>
    
    <!-- Botón de inicio -->
    <div class="home-button"><a href="index.php">Inicio</a></div>
</header>
    
<main>
    
    <?php
    if (isset($_GET['id'])) {
        $id_pelicula = $_GET['id'];

        // Conexión a la base de datos
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "ai36";

        $conn = new mysqli($servername, $username, $password, $dbname);

        // Verificar la conexión
        if ($conn->connect_error) {
            die("Conexión fallida: " . $conn->connect_error);
        }

        // Consulta para obtener detalles de la película
        $query_pelicula = "SELECT * FROM movie WHERE id = $id_pelicula";
        $resultado_pelicula = $conn->query($query_pelicula);
        $pelicula = $resultado_pelicula->fetch_assoc();

        // Consulta para obtener los géneros de la película
        $query_generos = "SELECT genre.name FROM genre
                          JOIN moviegenre mg ON genre.id = mg.genre
                          WHERE mg.movie_id = $id_pelicula";
        $resultado_generos = $conn->query($query_generos);

        // Consultamos la puntuación del usuario
        if (isset($_SESSION['id'])) {
            $id_usuario = $_SESSION['id'];
            $query_puntuacion = "SELECT score FROM user_score WHERE id_user='$id_usuario' AND id_movie='$id_pelicula'";
            $resultado_puntuacion = $conn->query($query_puntuacion);
            $puntuacion_usuario = $resultado_puntuacion->fetch_assoc()['score'];

            
        }

        // Consultamos la puntuación media dada por los usuarios
        $query_puntuacion_media = "SELECT AVG(score) AS avg_score FROM user_score WHERE id_movie='$id_pelicula'";
        $resultado_puntuacion_media = $conn->query($query_puntuacion_media);
        $puntuacion_media = $resultado_puntuacion_media->fetch_assoc()['avg_score'];
        
        echo '<div class="catalogo">';
        // Puntuación media de la película
    
        
        // Mostrar detalles de la película en una tabla
        echo '<table class="detalles-pelicula">';
        echo '<tr><td><strong>Título:</strong></td><td>' . htmlspecialchars($pelicula['title']) . '</td></tr>';
        echo '<tr><td><strong>Puntuación Media:</strong></td><td>' . number_format($puntuacion_media, 2) . '</td></tr>';
        echo '<tr><td colspan="2" style="text-align: center;"><img src="/imagenes/images/' . htmlspecialchars($pelicula['url_pic']) . '" style="width: 400px; height: 600px;"></td></tr>';
        echo '<tr><td><strong>Descripción:</strong></td><td>' . htmlspecialchars($pelicula['desc']) . '</td></tr>';
        echo '<tr><td><strong>Géneros:</strong></td><td>';

        $totalGeneros = $resultado_generos->num_rows;
        $counter = 1;
        
        while ($genero = $resultado_generos->fetch_assoc()) {
            echo htmlspecialchars($genero['name']);
        
            // Add a comma if it's not the last genre
            if ($counter < $totalGeneros) {
                echo ', ';
            }
        
            $counter++;
        }
        
        echo '</td></tr>';
        echo '</table>';
        
        
        
        // Mostrar imagen de la película
       // echo '<img src="/imagenes/images/' . htmlspecialchars($pelicula['url_pic']) . '" width="200" height="600"><br>';
        


// Mostrar formulario de comentarios si el usuario ha iniciado sesión
if (isset($_SESSION['name']) && isset($_SESSION['id'])) {
    echo "<div class='comment-form-container'>";
    echo "<form action='' method='POST' class='comment-form'>";
    echo "<input type='text' name='comentario' placeholder='Añade un comentario...'>";
    echo "<input type='submit' value='Enviar comentario'>";
    echo "</form>";
    echo "</div>";
    // Procesamiento del formulario de comentarios
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $comentario = $_POST['comentario'];
        $id_usuario = $_SESSION['id'];

        // Consulta para insertar el comentario en la base de datos
        $query_insert_comentario = "INSERT INTO moviecomments (movie_id, user_id, comment) VALUES ('$id_pelicula', '$id_usuario', '$comentario')";
        $result_insert_comentario = $conn->query($query_insert_comentario);

        if ($result_insert_comentario) {
            echo "<p>Comentario enviado correctamente.</p>";
            header("Refresh:0");
        } else {
            echo "<p>Error al enviar el comentario.</p>";
        }
    }
} else {
    echo "<p>Inicia sesión <a href='login.php'>aquí</a> primero para añadir un comentario.</p>";
}

// Mostrar comentarios de la película
$query_comentarios = "SELECT users.name, moviecomments.comment 
                      FROM moviecomments 
                      JOIN users ON moviecomments.user_id = users.id 
                      WHERE moviecomments.movie_id = $id_pelicula";
$resultado_comentarios = $conn->query($query_comentarios);

echo '<table class="comments-table">';
echo '<tr><th>Usuario</th><th>Comentario</th></tr>';

if ($resultado_comentarios->num_rows > 0) {
    while ($comentario = $resultado_comentarios->fetch_assoc()) {
        echo '<tr><td>' . htmlspecialchars($comentario['name']) . '</td><td>' . htmlspecialchars($comentario['comment']) . '</td></tr>';
    }
} else {
    echo '<tr><td colspan="2">No hay comentarios aún.</td></tr>';
}

echo '</table>';

// Check if the user is logged in
if (isset($_SESSION['name']) && isset($_SESSION['id'])) {
    // The user is logged in, show the rating form
    

/* Form para puntuar o cambiar la puntuación */ ?>
<form action="puntuar.php?var=<?php echo $id_pelicula; ?>" method="POST">

<?php

// Incluir lógica para procesar el formulario si se ha enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar si se ha enviado la variable 'puntuacion' y procesarla
    if (isset($_POST['puntuacion']) && is_numeric($_POST['puntuacion'])) {
        $puntuacion_usuario = $_POST['puntuacion'];
        // Aquí puedes realizar cualquier operación adicional con la puntuación, como almacenarla en una base de datos, etc.
        echo '<h2>Puntuación actualizada: ' . $puntuacion_usuario . '</h2>';
    }
}
if (isset($puntuacion_usuario) && is_numeric($puntuacion_usuario)) {
    echo '<h2>Puntuación dada anteriormente: ' . $puntuacion_usuario . '</h2>';
    ?>
    <br>
    <select name="puntuacion">
        <option selected value="0">Elige una opción</option>
        <option value="1" <?php echo ($puntuacion_usuario == 1) ? 'selected' : ''; ?>>1</option>
        <option value="2" <?php echo ($puntuacion_usuario == 2) ? 'selected' : ''; ?>>2</option>
        <option value="3" <?php echo ($puntuacion_usuario == 3) ? 'selected' : ''; ?>>3</option>
        <option value="4" <?php echo ($puntuacion_usuario == 4) ? 'selected' : ''; ?>>4</option>
        <option value="5" <?php echo ($puntuacion_usuario == 5) ? 'selected' : ''; ?>>5</option>
    </select>
    <input type="submit" value="Cambiar puntuación">
<?php
} else {
    echo '<h2>Sin puntuar</h2>';
    ?>
    <select name="puntuacion">
        <option selected value="0">Elige una opción</option>
        <option value="1">1</option>
        <option value="2">2</option>
        <option value="3">3</option>
        <option value="4">4</option>
        <option value="5">5</option>
    </select>
    <input type="submit" value="Puntuar">
</form>
<?php } 
} else {
    // The user is not logged in, show a message
    echo "<p>Para puntuar tienes que iniciar sesión primero.</p>";
}


// Cerrar la conexión
$conn->close();
} else {
echo "<p>No se ha proporcionado una película válida.</p>";
}
?>
</main>
</body>
</html>
