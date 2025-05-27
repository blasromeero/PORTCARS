<?php
require '../../SQL/db.php';
require 'utilidades.php';

try {
    $apikey = Utilities::validateMandatoryParameter($_GET, 'apikey');

    $database = new Database();
    $conn = $database->getConnection();

    $dniUsuario = Utilities::obtenerDniUsuario($conn, $apikey);

    if ($dniUsuario === null) {
        throw new Exception("El usuario no existe en la base de datos");
    } else {
        $sql = $conn->prepare("
            SELECT t.IDTransferencia, t.fechaTransferencia, tu.DNI_Propietario, tu.DNI_Receptor
            FROM Transferencia t
            INNER JOIN Transferencia_Usuario tu ON t.IDTransferencia = tu.IDTransferencia
            WHERE tu.DNI_Propietario = ?
            ORDER BY t.fechaTransferencia DESC
        ");
        $sql->bind_param("s", $dniUsuario);

        if ($sql->execute()) {
            $result = $sql->get_result();

            echo "<table border='1' cellpadding='8' cellspacing='0'>";
            echo "<tr>
                    <th>ID Transferencia</th>
                    <th>Fecha</th>
                    <th>Propietario</th>
                    <th>Receptor</th>
                  </tr>";

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['IDTransferencia']}</td>
                            <td>{$row['fechaTransferencia']}</td>
                            <td>{$row['DNI_Propietario']}</td>
                            <td>{$row['DNI_Receptor']}</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No se encontraron transferencias como propietario para este usuario.</td></tr>";
            }
        } else {
            throw new Exception("Error al consultar las transferencias. Consulte con el servicio técnico.");
        }

        $sql->close();
    }
} catch (Exception $e) {
    $respuestaHTML = "<p>ERROR AL MOSTRAR LAS TRANSFERENCIAS: {$e->getMessage()} </p>";
    echo $respuestaHTML;
}
