<h1>Prueba de conexión a MySQL</h1>
<h4>Realizando conexión a MySQL desde php...</h4>

<?php
echo __DIR__;

require '../../SQL/db.php';  // Asegúrate de proporcionar la ruta correcta

// Crear una instancia de la clase Database
$database = new Database();



// Intentar realizar la conexión
try {
    $conn = $database->getConnection();

    // Verificar la conexión
    if ($conn->connect_error) {
        echo "Error: Imposible conectar a MySQL." . PHP_EOL;
        echo "Error: " . $conn->connect_errno . " - " . $conn->connect_error . PHP_EOL;
        exit;
    }

    echo "Éxito: Una conexión correcta se ha realizado a MySQL." . PHP_EOL;
    echo "Información del host: " . mysqli_get_host_info($conn) . PHP_EOL;

    // Cerrar la conexión
    $conn->close();
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . PHP_EOL;
}