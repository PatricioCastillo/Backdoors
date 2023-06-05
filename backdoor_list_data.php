<?php

// Configuración de la conexión a la base de datos
$servername = "68.178.244.46";
$username = "dash_dos";
$password = "0mr3YU^,nl31";

// Crear la conexión a la base de datos
$conn = new mysqli($servername, $username, $password);

// Verificar si hay errores en la conexión
if ($conn->connect_error) {
    die("Error de conexión a la base de datos: " . $conn->connect_error);
}

// Consultar todas las bases de datos
$sql = "SHOW DATABASES";
$result = $conn->query($sql);

// Verificar si se encontraron bases de datos
if ($result->num_rows > 0) {
    // Abrir el archivo de bloc de notas
    $archivo = fopen("Listado_Databases.txt", "w");

    // Iterar sobre las bases de datos
    while ($row = $result->fetch_assoc()) {
        $database = $row['Database'];

        // Consultar todas las tablas de la base de datos
        $sql_tables = "SHOW TABLES FROM " . $database;
        $result_tables = $conn->query($sql_tables);

        // Verificar si se encontraron tablas
        if ($result_tables->num_rows > 0) {
            fwrite($archivo, "Base de datos: " . $database . "\n");
            
            // Iterar sobre las tablas
            while ($row_tables = $result_tables->fetch_assoc()) {
                $table = $row_tables['Tables_in_' . $database];
                fwrite($archivo, "\tTabla: " . $table . "\n");

                // Consultar los registros de la tabla
                $sql_records = "SELECT * FROM " . $database . "." . $table;
                $result_records = $conn->query($sql_records);

                // Verificar si se encontraron registros
                if ($result_records->num_rows > 0) {
                    fwrite($archivo, "\t\tRegistros:\n");

                    // Obtener los nombres de las columnas
                    $fields = $result_records->fetch_fields();
                    $column_names = array();
                    foreach ($fields as $field) {
                        $column_names[] = $field->name;
                    }
                    fwrite($archivo, "\t\t" . implode("\t", $column_names) . "\n");

                    // Iterar sobre los registros
                    while ($row_records = $result_records->fetch_assoc()) {
                        $record_values = array();
                        foreach ($row_records as $value) {
                            $record_values[] = $value;
                        }
                        fwrite($archivo, "\t\t" . implode("\t", $record_values) . "\n");
                    }
                } else {
                    fwrite($archivo, "\t\tNo se encontraron registros\n");
                }
            }
        }
    }

    // Cerrar el archivo
    fclose($archivo);

    echo "Los datos se han guardado en el archivo datos.txt";
} else {
    echo "No se encontraron bases de datos";
}

// Cerrar la conexión a la base de datos
$conn->close();

?>
