<?php
$host = "bqhj3xvf9exii2ciawuu-mysql.services.clever-cloud.com";
$dbusername = "unm8tt3u8aggqmkm";
$dbpassword = "DrdU5mrmfbPyKWn2Oycn";
$dbname = "bqhj3xvf9exii2ciawuu";

$conn = new mysqli($host, $dbusername, $dbpassword, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// CREATE - Inserta datos en la base de datos
if(isset($_POST['enviar'])) {
    $nombre = $_POST['nombre'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];
    $genero = $_POST['genero'];
    $estado = $_POST['estado'];

    $SELECT = "SELECT telefono FROM usuario WHERE telefono = ?";
    $INSERT = "INSERT INTO usuario (nombre, password, email, telefono, genero, estado) VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($SELECT);
    $stmt->bind_param("s", $telefono);
    $stmt->execute();
    $stmt->store_result();
    $rnum = $stmt->num_rows;
    $stmt->close();

    if ($rnum == 0) {
        $stmt = $conn->prepare($INSERT);
        $stmt->bind_param("ssssss", $nombre, $password, $email, $telefono, $genero, $estado);
        $stmt->execute();
        echo "Registro creado exitosamente.";
    } else {
        echo "El número de teléfono ya está registrado.";
    }
}

// READ - Muestra datos de la base de datos
$sql = "SELECT * FROM usuario";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<table>";
    echo "<tr><th>Nombre</th><th>Email</th><th>Teléfono</th><th>Género</th><th>Estado</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr><td>" . $row["nombre"] . "</td><td>" . $row["email"] . "</td><td>" . $row["telefono"] . "</td><td>" . $row["genero"] . "</td><td>" . $row["estado"] . "</td></tr>";
    }
    echo "</table>";
} else {
    echo "0 resultados.";
}

// UPDATE - Actualiza datos en la base de datos
if(isset($_POST['actualizar'])) {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];
    $genero = $_POST['genero'];
    $estado = $_POST['estado'];

    $UPDATE = "UPDATE usuario SET nombre=?, email=?, telefono=?, genero=?, estado=? WHERE id=?";

    $stmt = $conn->prepare($UPDATE);
    $stmt->bind_param("sssssi", $nombre, $email, $telefono, $genero, $estado, $id);
    $stmt->execute();
    echo "Registro actualizado exitosamente.";
}

// DELETE - Elimina datos de la base de datos
if(isset($_POST['eliminar'])) {
    $id = $_POST['id'];

    $DELETE = "DELETE FROM usuario WHERE id=?";

    $stmt = $conn->prepare($DELETE);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    echo "Registro eliminado exitosamente.";
}

// CLOSE - Cierra la conexión
$conn->close();
?>
