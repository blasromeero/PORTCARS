<?php
require '../../SQL/db.php';
require 'utilidades.php';

try {
    $apikey = Utilities::validateMandatoryParameter($_GET, 'apikey');

    $database = new Database();
    $conn = $database->getConnection();

    $dniUsuario = Utilities::obtenerDniUsuario($conn, $apikey)
    

    if($dniUsuario === null){
        throw new Exception("El usuario no existe en la base de datos");
    } else {

    $sql = $conn->prepare("SELECT IDImportacion, fechaImportacion, pais, estado, VIN FROM Importacion WHERE DNIUsuario = ? ORDER BY fechaImportacion DESC");
    $sql->bind_param("s", $dniUsuario);
    
    if ($sql->execute()) {
        $result = $sql->get_result();
    
        echo "<table border='1' cellpadding='8' cellspacing='0'>";
        echo "<tr>
                <th>ID</th>
                <th>Fecha</th>
                <th>País</th>
                <th>Estado</th>
                <th>VIN</th>
              </tr>";
        
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['IDImportacion']}</td>
                        <td>{$row['fechaImportacion']}</td>
                        <td>{$row['pais']}</td>
                        <td>{$row['estado']}</td>
                        <td>{$row['VIN']}</td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='5'>No se encontraron importaciones para este usuario.</td></tr>";
        }
    } else {
        throw new Exception("Error al consultar el usuario. Consulte con el servicio técnico.");
    }

}
    $sql->close();
} catch (Exception $e) {
    $respuestaHTML = "<p>ERROR AL MOSTRAR LAS IMPORTACIONES: {$e->getMessage()} </p>";
    echo $respuestaHTML;
}