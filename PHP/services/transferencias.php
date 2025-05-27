<?php
require '../../SQL/db.php';
require 'utilidades.php';

try {
    $dniReceptor = Utilities::validateMandatoryParameter($_GET, 'dniReceptor');
    $vinVehiculo = Utilities::validateMandatoryParameter($_GET, 'vinVehiculo');
    $nombreModelo = Utilities::validateMandatoryParameter($_GET, 'nombreModelo');
    $apikey = Utilities::validateMandatoryParameter($_GET, 'apikey');

    $database = new Database();
    $conn = $database->getConnection();

    // Obtener DNI del propietario a partir de la API key
    $dniPropietario = Utilities::obtenerDniUsuario($conn, $apikey);

    if ($dniPropietario === null) {
        throw new Exception("El usuario no existe en la base de datos.");
    }

    // Insertar en tabla Transferencia con fecha de hoy desde MySQL
    $insertTransferencia = $conn->prepare("INSERT INTO Transferencia (fechaTransferencia) VALUES (CURDATE())");

    if (!$insertTransferencia->execute()) {
        throw new Exception("No se pudo registrar la transferencia.");
    }

    // Obtener el ID de la última transferencia insertada
    $idTransferencia = $conn->insert_id;

    // Insertar en tabla Transferencia_Usuario
    $insertRelacion = $conn->prepare("
        INSERT INTO Transferencia_Usuario (IDTransferencia, DNI_Propietario, DNI_Receptor)
        VALUES (?, ?, ?)
    ");
    $insertRelacion->bind_param("iss", $idTransferencia, $dniPropietario, $dniReceptor);

    if (!$insertRelacion->execute()) {
        throw new Exception("No se pudo registrar la relación de transferencia.");
    }

    $matriucla = Utilities::generarMatricula();
    // Insertar en tabla Vehiculo
    $insertVehiculo = $conn->prepare("
        INSERT INTO Vehiculo (VIN, nombreModelo, DNI_Propietario)
        VALUES (?, ?, ?)
    ");
    $insertVehiculo->bind_param("sss", $vinVehiculo, $nombreModelo, $dniReceptor); // El nuevo dueño es el receptor

    if (!$insertVehiculo->execute()) {
        throw new Exception("No se pudo registrar el vehículo.");
    }

    echo "<p>Transferencia registrada correctamente con Nº: $idTransferencia</p>";

    $insertTransferencia->close();
    $insertRelacion->close();
    $insertVehiculo->close();
} catch (Exception $e) {
    $respuestaHTML = "<p>ERROR AL REGISTRAR LA TRANSFERENCIA: {$e->getMessage()}</p>";
    echo $respuestaHTML;
}
