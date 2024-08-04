<?php
session_start();
?>

<!DOCTYPE HTML>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VideoPelis</title>
    <link rel="stylesheet" type="text/css" href="signup.css">
</head>

<header>
    <h1>Registro de usuario</h1>

    <?php
    // Verifica si el usuario ha iniciado sesión
    if (isset($_SESSION['name'])) {
        echo '<div class="welcome-msg">¡Bienvenido, ' . $_SESSION['name'] . '!</div>';
        echo '<div class="profile-button"><a href="profile.php">Perfil</a></div>';
        echo '<div class="logout-button"><a href="logout.php">Cerrar sesión</a></div>';
    } else

    ?>
    
    <!-- Botón de inicio -->
    <div class="home-button"><a href="index.php">Inicio</a></div>
</header>
<?php


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "ai36";

    // Crear conexión
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificar la conexión
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    $contraseña = md5($_POST['passwd']);

    if (
        !empty($_POST['name']) &&
        !empty($_POST['edad']) &&
        !empty($_POST['sex']) &&
        !empty($_POST['ocupacion']) &&
        !empty($_POST['passwd'])
    ) {
        $nombre = $_POST['name'];

        // Consulta para comprobar si el usuario ya existe
        $stmt = $conn->prepare("SELECT * FROM users WHERE name = ?");
        $stmt->bind_param("s", $nombre);
        $stmt->execute();
        $result = $stmt->get_result();
        $contador = $result->num_rows;

        // Comprobar si el usuario ya existe
        if ($contador == 0) {
            $edad = $_POST['edad'];
            $sexo = $_POST['sex'];
            $ocupacion = $_POST['ocupacion'];
            $foto = $_POST['pic'];

            // Preparar y ejecutar la consulta para insertar el usuario
            $stmt_insert = $conn->prepare("INSERT INTO users (name, passwd, edad, sex, ocupacion, pic) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt_insert->bind_param("ssisss", $nombre, $contraseña, $edad, $sexo, $ocupacion, $foto);
            $stmt_insert->execute();

            if ($stmt_insert) {
                echo "<h1>¡Se ha registrado su usuario!</h1>";
            } else {
                echo "<h1>Se ha producido un error al registrar el usuario</h1>";
            }
        } else {
            echo "<h1>Ups, ya hay un usuario registrado anteriormente</h1>";
        }

        if (isset($_FILES['pic']) && $_FILES['pic']['error'] == UPLOAD_ERR_OK) {
            // Crear una ruta de destino para los archivos cargados
            $uploadDir = 'D:/xampp/htdocs/imagenes/images/';

            $uploadFile = $uploadDir . basename($_FILES['pic']['name']);
    
            // Intenta mover el archivo cargado a la ruta de destino
            if (move_uploaded_file($_FILES['pic']['tmp_name'], $uploadFile)) {
                echo "Archivo cargado correctamente.\n";
                $foto = $_FILES['pic']['name']; // Guardar nombre de archivo en base de datos
            } else {
                echo "Error al cargar el archivo.\n";
            }
        }
    }
    $conn->close();
}
?>

<main>
        <h2>Introduce tus datos de nuevo usuario</h2>
        <form class ="registration-table"action="" method="post">
            <label for="name">Nombre de usuario:</label>
            <input type="text" name="name" required><br><br>

            <label for="edad">Edad:</label>
            <input type="number" name="edad" required><br><br>

            <label for="sex">Sexo:</label>
            <select name="sex" required>
                    <option value="F">F</option>
                    <option value="M">M</option>
                </select><br><br>

            <label for="ocupacion">Ocupación:</label>
                <select name="ocupacion" required>
                    <option value="administrator">Administrator</option>
                    <option value="artist">Artist</option>
                    <option value="educator">Educator</option>
                    <option value="engineer">Engineer</option>
                    <option value="entertainment">Entertainment</option>
                    <option value="executive">Executive</option>
                    <option value="homemaker">Homemaker</option>
                    <option value="lawyer">Lawyer</option>
                    <option value="librarian">Librarian</option>
                    <option value="marketing">Marketing</option>
                    <option value="programmer">Programmer</option>
                    <option value="scientist">Scientist</option>
                    <option value="student">Student</option>
                    <option value="technician">Technician</option>
                    <option value="writer">Writer</option>
                    <option value="others">Others</option>
                </select><br><br>


            <label for="passwd">Contraseña:</label>
            <input type="password" name="passwd" required><br><br>

            <label for="pic">Foto:</label>
            <input type="file" name="pic" required><br><br>

            <input type="submit" value="Registrarse">
        </form>
    </main>