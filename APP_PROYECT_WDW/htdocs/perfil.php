<?php
session_start();
?>

<!DOCTYPE HTML>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VideoPelis</title>
    <link rel="stylesheet" type="text/css" href="perfil.css">
</head>

<body>
<header>
    <h1>Detalles</h1>

    <?php
    if (isset($_SESSION['name'])) {
        echo '<div class="welcome-msg">¡Bienvenido, ' . htmlspecialchars($_SESSION['name']) . '!</div>';
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
    <?php
    //Conectamos con la base de datos para mostrar los datos de usuario
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "ai36";

    $conn = new mysqli($servername, $username, $password, $dbname);
   

    $id = $_SESSION['id'];
    $sql = "SELECT * FROM users WHERE id = '$id'";
    $result = $conn->query($sql);

    while ($row = $result->fetch_assoc()) {

        // Asegúrate de que la ruta a las imágenes esté correcta
        $imagePath = '/imagenes/images/' . $row['pic'];
    ?>
        <!-- Mostramos los datos en una tabla -->
 <div class="datos">
        <table>
            <tr>
                <td><strong>Nombre:</strong></td>
                <td><?php echo $row['name']; ?></td>
            </tr>
            <tr>
                <td><strong>Edad:</strong></td>
                <td><?php echo $row['edad']; ?></td>
            </tr>
            <tr>
                <td><strong>Sexo:</strong></td>
                <td><?php echo $row['sex']; ?></td>
            </tr>
            <tr>
                <td><strong>Ocupación:</strong></td>
                <td><?php echo $row['ocupacion']; ?></td>
            </tr>
        </table>
        <br>
        <div>
            <strong>Foto de perfil:</strong><br>
            <img src="<?php echo $imagePath; ?>" alt="Foto de perfil" />
        </div>
    </div>
        <div class="button-container">
        <!-- Formulario para volver -->
        <form action="index.php">
        <input type="submit" name="volver" value="Volver">
        </form>
        <br>
        <!-- Formulario para modificar los datos de usuario -->
        <form action="modificar.php">
        <input type="submit" name="modificar" value="Modificar datos">
        </form>
        </div>

    <?php
    }
    $conn->close();
    ?>
</body>

</html>
