<?php
session_start();
?>
<head>
    <meta charset="UTF-8">
    <title>Catálogo de Películas</title>
    <link rel="stylesheet" href="index.css">
</head>
<header>
    <h1>Encabezado</h1>

    <?php
 
    // Verifica si el usuario ha iniciado sesión
    if (isset($_SESSION['name'])) {
        echo '<div class="welcome-msg">¡Bienvenido, ' . $_SESSION['name'] . '!</div>';
        echo '<div class="profile-button"><a href="profile.php">Perfil</a></div>';
        echo '<div class="logout-button"><a href="logout.php">Cerrar sesión</a></div>';
    } else {
        echo '
        <div class="login-form">
            <h2>Iniciar sesión</h2>
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

<div class="catalogo">
<?php
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $selectedCategory = $_GET["categoria"];

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "ai36";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    $sql = "SELECT DISTINCT  m.* FROM movie m 
            INNER JOIN moviegenre mg ON m.id = mg.movie_id
            INNER JOIN genre g ON mg.genre = g.id
            WHERE g.name = '$selectedCategory'";

    $result = $conn->query($sql);

    if ($result) {
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='pelicula'>";
                echo "<h2>" . $row["title"] . "</h2>";
                echo '<td>
                <a href="detalles_pelicula.php?id=' . htmlspecialchars($row['id']) . '">
                <img src="/imagenes/images/' . htmlspecialchars($row['url_pic']) . '" width="500" height="250">
                </a>
                </td>';
                echo "<p>Descripción: " . $row["desc"] . "</p>";
                echo "</div>";
            }
        } else {
            echo "No se encontraron películas para la categoría seleccionada.";
        }
    } else {
        echo "Error en la consulta: " . $conn->error;
    }

    $conn->close();
    } else {
    echo "Ha ocurrido un error al procesar la solicitud.";
    }

?>
</div>
