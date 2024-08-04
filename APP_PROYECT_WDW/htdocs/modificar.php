<!DOCTYPE HTML>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="modificar.css">
</head>

<body>


    <header>
    <h1>Modificar</h1>

    <?php
    if (isset($_SESSION['name'])) {
        echo '<div class="welcome-msg">¡Bienvenido, ' . htmlspecialchars($_SESSION['name']) . '!</div>';
        echo '<div class="logout-button"><a href="logout.php">Cerrar sesión</a></div>';
    } else {

    }
    ?>
    
    <!-- Botón de inicio -->
    <div class="home-button"><a href="index.php">Inicio</a></div>
    </header>
    <?php


    // Iniciamos la sesión y realizamos la conexión a la base de datos
    session_start();
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "ai36";
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Recibimos los nuevos datos
        $nuevoNombre = !empty($_POST['nuevonombre']) ? $_POST['nuevonombre'] : $_SESSION['name'];
        $nuevaEdad = !empty($_POST['nuevaedad']) ? $_POST['nuevaedad'] : $_SESSION['edad'];
        $nuevoSexo = !empty($_POST['nuevosexo']) ? $_POST['nuevosexo'] : $_SESSION['sex'];
        $nuevaPasswd = !empty($_POST['nuevapasswd']) ? md5($_POST['nuevapasswd']) : $_SESSION['passwd'];
        $nuevaOcupacion = !empty($_POST['nuevaocupacion']) ? $_POST['nuevaocupacion'] : $_SESSION['ocupacion'];
        $id = $_SESSION['id'];

        // Procesamos la foto de perfil si se ha subido una nueva
        if (isset($_FILES['nuevaFoto']) && $_FILES['nuevaFoto']['error'] == UPLOAD_ERR_OK) {
            // Asegúrate de que el directorio de carga existe y es escribible
            $uploadDir = '/imagenes/images/'; // Especifica el directorio correcto
            //$uploadFile = $uploadDir . basename($_FILES['nuevaFoto']['name']);
            $uploadFile = basename($_FILES['nuevaFoto']['name']);

            // Intenta mover el archivo subido al directorio de destino
            if (move_uploaded_file($_FILES['nuevaFoto']['tmp_name'], $uploadFile)) {
                echo "El archivo ha sido subido con éxito.\n";
                $nuevaFotoDePerfil = basename($_FILES['nuevaFoto']['name']); // Solo el nombre del archivo
            } else {
                echo "Hubo un error al subir el archivo.\n";
                $nuevaFotoDePerfil = null;
            }
        } else {
            // Si no se subió una nueva foto, mantenemos la existente
            $nuevaFotoDePerfil = $_POST['fotoActual'];
        }

        // Preparamos y ejecutamos la actualización, incluida la nueva ruta de la foto si está disponible
        if ($nuevaFotoDePerfil) {
            $update_query = "UPDATE users SET name=?, edad=?, sex=?, passwd=?, ocupacion=?, pic=? WHERE id=?";
            $stmt = $conn->prepare($update_query);
            $stmt->bind_param("sissssi", $nuevoNombre, $nuevaEdad, $nuevoSexo, $nuevaPasswd, $nuevaOcupacion, $nuevaFotoDePerfil, $id);
        } else {
            // Si no hay una nueva foto, no actualizamos la columna de la foto
            $update_query = "UPDATE users SET name=?, edad=?, sex=?, passwd=?, ocupacion=? WHERE id=?";
            $stmt = $conn->prepare($update_query);
            $stmt->bind_param("sisssi", $nuevoNombre, $nuevaEdad, $nuevoSexo, $nuevaPasswd, $nuevaOcupacion, $id);
        }

        // Ejecutamos la consulta
        if ($stmt->execute()) {
            echo "<p>Los datos se han actualizado correctamente.</p>";
        } else {
            echo "<p>Error al actualizar los datos.</p>";
        }

        $stmt->close();
    }

    // Consulta para obtener los datos actuales del usuario
    $id = $_SESSION['id'];
    $sql = "SELECT * FROM users WHERE id = '$id'";
    $result = $conn->query($sql);

    // Mostrar los datos en una tabla
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $fotoActual = $row['pic'];
    ?>

            <div class="datos">
                <h2>Datos actuales del usuario:</h2>
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
                    <tr>
                        <td><strong>Foto de perfil actual:</strong></td>
                        <td><?php echo $fotoActual; ?></td>
                    </tr>
                </table>
            </div>

    <?php
        }
    } else {
        echo "No se encontraron datos del usuario.";
    }

    $conn->close();
    ?>

    <!-- Formulario para modificar los datos de usuario en forma de tabla -->
    <form action="" method="POST" enctype="multipart/form-data">
        <h2>Nuevos datos de usuario:</h2>
        <table>
            <tr>
                <td><h3>Nuevo nombre:</h3></td>
                <td><input type="text" name="nuevonombre"></td>
            </tr>
            <tr>
                <td><h3>Nueva edad:</h3></td>
                <td><input type="number" name="nuevaedad"></td>
            </tr>
            <tr>
                <td><h3>Nuevo sexo:</h3></td>
                <td>
                    <input type="radio" name="nuevosexo" value="M"> Masculino
                    <input type="radio" name="nuevosexo" value="F"> Femenino
                </td>
            </tr>
            <tr>
                <td><h3>Nueva contraseña:</h3></td>
                <td><input type="password" name="nuevapasswd"></td>
            </tr>
            <tr>
                <td><h3>Nueva ocupación:</h3></td>
                <td>
                    <select name="nuevaocupacion">
                    <option selected value="0"> Elige una opción</option>

                                <option value="administrator">Administrador</option>
                                <option value="artist">Artista</option>
                                <option value="doctor">Médico</option>
                                <option value="educator">Profesor</option>
                                <option value="engineer">Ingeniero</option>
                                <option value="entertainment">Youtuber</option>
                                <option value="executive">Ejecutivo</option>
                                <option value="healthcare">Nutricionista</option>
                                <option value="homemaker">Amo/a de casa</option>
                                <option value="lawyer">Abogado</option>
                                <option value="librarian">Bibliotecario</option>
                                <option value="marketing">Marketing</option>
                                <option value="none">Desocupado</option>
                                <option value="programmer">Programador</option>
                                <option value="retired">Jubilado</option>
                                <option value="salesman">Comercial</option>
                                <option value="scientist">Científico</option>
                                <option value="student">Estudiante</option>
                                <option value="technician">Técnico</option>
                                <option value="writer">Escritor</option>
                                <option value="other">Otro</option>
                                
                    </select>
                </td>
            </tr>
            <tr>
                <td><h3>Nueva foto de perfil:</h3></td>
                <td><input type="file" name="nuevaFoto" accept="image/*"></td>
            </tr>
        </table>
        <!-- Asumiendo que tienes una variable $fotoActual con la foto de perfil actual -->
        <input type="hidden" name="fotoActual" value="<?php echo $fotoActual; ?>">
        <!-- Botón para enviar los datos y realizar cambios -->
        <input type="submit" name="cambiar" value="Cambiar">
    </form>

    <!-- Botón para volver -->
    <button onclick="window.location.href='perfil.php';">Volver</button>
</body>

</html>
