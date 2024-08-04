<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VideoPelis</title>
    <link rel="stylesheet" type="text/css" href="puntuar.css">
</head>

<body>

    <?php
    session_start();

    // Nos conectamos a la base de datos para obtener las puntuaciones
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "ai36";

    try {
        $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("No se puede conectar a la base de datos: " . $e->getMessage());
    }

    // Obtener datos del formulario
    $puntuacion = $_POST['puntuacion'];
    $idpelicula = $_GET['var'];
    $idusuario = $_SESSION['id'];

    // Consultar si ya hay una puntuación dada por el usuario
    $sql = "SELECT score FROM user_score WHERE id_user = :idusuario AND id_movie = :idpelicula";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':idusuario', $idusuario);
    $stmt->bindParam(':idpelicula', $idpelicula);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        // Actualizamos el valor
        $sql2 = "UPDATE user_score SET score = :puntuacion WHERE id_user = :idusuario AND id_movie = :idpelicula";
        $stmt2 = $pdo->prepare($sql2);
        $stmt2->bindParam(':puntuacion', $puntuacion);
        $stmt2->bindParam(':idusuario', $idusuario);
        $stmt2->bindParam(':idpelicula', $idpelicula);
        $stmt2->execute();
    ?>
        <h1>¡Se ha actualizado su puntuación!</h1>
    <?php
    } else {
        // Si no había puntuado, puntúa
        $sql3 = "INSERT INTO user_score (id_user, id_movie, score) VALUES (:idusuario, :idpelicula, :puntuacion)";
        $stmt3 = $pdo->prepare($sql3);
        $stmt3->bindParam(':idusuario', $idusuario);
        $stmt3->bindParam(':idpelicula', $idpelicula);
        $stmt3->bindParam(':puntuacion', $puntuacion);
        $stmt3->execute();
    ?>
        <h1>¡Se ha añadido su puntuación!</h1>
    <?php
    }
    ?>

    <form action="index.php">
        <!-- Botón para volver a la lista de películas -->
        <button type="submit">Volver a las películas</button>
    </form>

</body>

</html>
