
<?php
session_start();
?>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VideoPelis</title>
    <link rel="stylesheet" type="text/css" href="login.css">
</head>

<?php
?>
<div class="container">

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

    $username_input = $_POST['username'];
    $password_input = md5($_POST['password']);
    

    // Consulta para validar el inicio de sesión
    $sql = "SELECT * FROM users WHERE name = '$username_input' AND passwd = '$password_input'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Usuario autenticado correctamente
        $usuario = $result->fetch_assoc(); // Obtenemos los datos del usuario
        $_SESSION['name'] = $username_input; // Almacenar el nombre de usuario en la sesión
        $_SESSION['id'] = $usuario['id']; // Guardamos la ID del usuario logeado.
        echo "¡Inicio de sesión exitoso para $username_input!";
        header("Location: index.php"); // Redirigir a la página principal
    } else {
        echo "<h2>¡Contraseña o usuario incorrectos!</h2>";
        echo '<form action="index.php"><button type="submit">Volver a la pagina principal</button></form>';
    }

    $conn->close(); // Cerrar la conexión
}

?>
</div>