<!DOCTYPE HTML>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VideoPelis</title>
    <link rel="stylesheet" type="text/css" href="estilo_perfil.css">
</head>
<body>
    <?php 
    //Conectamos con la base de datos para mostrar los datos de usuario
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "ai36";

    // 创建连接
    $conn = new mysqli($servername, $username, $password, $dbname);

    // 检查连接
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // 检查表是否存在
    $checkTable = $conn->query("SHOW TABLES LIKE 'user_recommendations'");
    if($checkTable->num_rows == 0) {
        // SQL 创建表的命令
        $sql = "CREATE TABLE user_recommendations (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            movie_id INT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";

        // 执行SQL命令
        if ($conn->query($sql) === TRUE) {
            echo "Table user_recommendations created successfully";
        } else {
            echo "Error creating table: " . $conn->error;
        }
    } else {
        echo "Table user_recommendations already exists";
    }

    // 关闭连接
    $conn->close();
    ?>
</body>
</html>
