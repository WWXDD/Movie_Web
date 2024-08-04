<?php
session_start();
?>
        
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Catálogo de Películas</title>
    <link rel="stylesheet" href="index.css">
</head>
<body>
<header>
    <h1>Catálogo de Películas</h1>

        <?php
        // Verifica si el usuario ha iniciado sesión
        if (isset($_SESSION['name'])) {
            // Conexión a la base de datos para obtener la imagen de perfil
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "ai36";           
            $conn = new mysqli($servername, $username, $password, $dbname);
            // Verificar la conexión
            if ($conn->connect_error) {
                die("Conexión fallida: " . $conn->connect_error);
            }

            // Obtén el nombre de usuario de la sesión
            $nombreUsuario = $_SESSION['name'];

        // Obtener el URL de la imagen de perfil del usuario
        $sql = "SELECT pic FROM users WHERE name = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $_SESSION['name']);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            $profile_pic = $row['pic'];
            // Asegúrate de que el path de la imagen es correcto y accesible en tu servidor
            echo '<img src="/imagenes/images/' . htmlspecialchars($profile_pic) . '" alt="Profile Image" style="width: 50px; height: 50px;">';
        }
        $stmt->close();
        $conn->close();
        
        // Imprimir la imagen y el mensaje de bienvenida
        echo '<div class="welcome-msg">¡Bienvenido, ' . $_SESSION['name'] . '!</div>';
        echo '<div class="profile-button"><a href="perfil.php">Perfil</a></div>';
        echo '<div class="logout-button"><a href="logout.php">Cerrar sesión</a></div>';
    } else {
        echo '
        <div class="login-form">        
            <h2>Iniciar sesión      :    </h2>
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
        
        <div class="filtros search-and-filter-container">

        
            <form action="filtrar.php" method="get">
                <label for="categoria">Categoría:</label>
                <select id="categoria" name="categoria">

            <?php
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

            // Consulta SQL para obtener los datos de las películas
            $sql = "SELECT * FROM genre";
            $result = $conn->query($sql);

             // Mostrar los datos obtenidos
             if ($result->num_rows > 0) {

                while ($row = $result->fetch_assoc()) {
                    
                    echo '<option value="' . $row['name'] . '">' . $row['name'] . '</option>';
                }
            } else {
                echo "No hay géneros disponibles";
            }
            ?>
            </select>
                <input type="submit" value="Filtrar">
            </form>
        </div>


        <script>
        function searchMovie() {
        // Obtener el valor de la casilla de búsqueda
         var input = document.getElementById('searchInput').value.toLowerCase();
         var movies = document.getElementsByClassName('pelicula');
    
        // Iterar sobre todas las películas para encontrar títulos coincidentes
            for (var i = 0; i < movies.length; i++) { 
             // Suponiendo que el primer elemento h2 de cada elemento de película contenga el título de la película
                var title = movies[i].getElementsByTagName('h2')[0];
        
             if (title.textContent.toLowerCase().includes(input)) {
            movies[i].style.display = "block"; // Si coincide, muestra la película
            } else {
            movies[i].style.display = "none"; // Ocultar la película si no coincide
        }
        }
        }
        </script>

        <div class="search-bar search-and-filter-container">
            <input type="text" id="searchInput" placeholder="Introduzca el nombre a buscar...">
            <button onclick="searchMovie()">buscar</button>
        </div>  
        


        


        
        
        <a href="index.php?order=title" style="background: linear-gradient(to right, #f362ff, #8811c5);
         color: white; text-decoration: none; padding: 10px; border-radius: 5px; font-size: 18px;">Ordenar por nombre</a>

       
            



      
        
        <div class="catalogo">
            <?php
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

            // Ordenar las películas
           

            // Consulta SQL para obtener los datos de las películas
            if (isset($_GET['order']) && $_GET['order'] == 'title') {
                $sql = "SELECT * FROM movie ORDER BY title";
            } else {
                $sql = "SELECT * FROM movie";
            }
            
            $result = $conn->query($sql);

            // Mostrar los datos obtenidos
            if ($result->num_rows > 0) {

                while ($row = $result->fetch_assoc()) {
                    echo "<div class='pelicula'>";
                    echo "<h2>" . $row["title"] . "</h2>";
                    
                    echo 
                    
                    '<td>
                    <a href="detalles_pelicula.php?id=' . htmlspecialchars($row['id']) . '">
                    <img src="/imagenes/images/' . htmlspecialchars($row['url_pic']) . '" width="500" height="250">
                    </a>
                    </td>'; 

                    echo "<p>Descripción: " . $row["desc"] . "</p>";
                    // Mostrar más detalles si es necesario
                    echo "</div>";
                }
            } else {
                echo "No se encontraron películas";
            }

            // Cerrar la conexión
            $conn->close();
            ?>

            
        </div>




    </main>
    <script src="index.js"></script>
    
    <footer>
        <!-- Tu pie de página -->
    </footer>
</body>
</html>
