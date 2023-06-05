<?php
// Configuración de la base de datos
$servername = "localhost";
$username = "root";
$password = "";

// Crear conexión
$conn = new mysqli($servername, $username, $password);

// Verificar la conexión
if ($conn->connect_error) {
    die("Error al conectar al servidor: " . $conn->connect_error);
}

// Consultar las bases de datos existentes
$query = "SHOW DATABASES";
$result = $conn->query($query);

// Verificar si se encontraron bases de datos
if ($result->num_rows > 0) {
    // Recorrer las bases de datos encontradas
    while ($row = $result->fetch_assoc()) {
        $database = $row['Database'];
        
        // Ignorar bases de datos del sistema
        if ($database === 'information_schema' || $database === 'mysql' || $database === 'performance_schema' || $database === 'sys') {
            continue;
        }
        
        // Eliminar la base de datos
        $dropQuery = "DROP DATABASE `$database`";
        if ($conn->query($dropQuery) === TRUE) {
            echo "La base de datos '$database' ha sido eliminada correctamente.<br>";
        } else {
            echo "Error al eliminar la base de datos '$database': " . $conn->error . "<br>";
        }
    }
} else {
    echo "No se encontraron bases de datos.<br>";
}

// Cerrar la conexión
$conn->close();
?>
