<?php
require '../../SQL/db.php';
require 'utilidades.php';

try {
    $matriculaVehiculo = Utilities::validateMandatoryParameter($_GET, 'matricula');
    $paisDestino = Utilities::validateMandatoryParameter($_GET, 'paisDestino');
    $apikey = Utilities::validateMandatoryParameter($_GET, 'apikey');


    $database = new Database();
    $conn = $database->getConnection();

    $apikeyValida = Utilities::checkApikey($conn,$apikey);
    $dniUsuario = Utilities::obtenerDniUsuario($conn,$apikey);
    $vinVehiculo == Utilities::obtenerVINConMatricula($conn,$matriculaVehiculo);

    if($vinVehiculo === null){
        throw new Exception("La matricula del vehiculo no existe!");
    } else {
        if($apikeyValida === True){
            $sql = $conn->prepare("INSERT INTO Exportacion (fechaExportacion, pais, estado, DNIUsuario, VIN) VALUES (CURDATE(), ?,'Pendiente',?,?)");
            $sql->bind_param("sss", $paisDestino,$dniUsuario,$vinVehiculo);
        
            if ($sql->execute()) {
                if ($sql->affected_rows > 0) {

                    $sql = $conn->prepare("DELETE FROM Vehiculo WHERE matricula = ?");
                    $sql->bind_param("s", $matriculaVehiculo);

                    if ($sql->execute()) {
                        if ($sql->affected_rows > 0) {
                            $respuestaHTML = "<p>Se ha iniciado el proceso de exportacion del vehiculo correctamente! Gracias por usar PORTCARS</p>";
                    echo $respuestaHTML;
                        } else {
                            $respuestaHTML = "<p>No se ha podido hacer la exportacion, contacte con servicio tecnico</p>";
                            echo $respuestaHTML;
                        }    
                    } else {
                        throw new Exception("El vehiculo no se ha podido eliminar de la base de datos, consulte con servicio tecnico.");
                    }
                } else {
                    $respuestaHTML = "<p>No se ha podido hacer la exportacion, contacte con servicio tecnico</p>";
                    echo $respuestaHTML;
                }    
            } else {
                throw new Exception("Error al insertar la exportacion en la base de datos");
            }
        } else {
            throw new Exception("La APIKEY del usuario no es valida!");
        }
    }

    $sql->close();
} catch (Exception $e) {
    $respuestaHTML = "<p>ERROR AL REALIZAR UNA EXPORTACIOn: {$e->getMessage()} </p>";
    echo $respuestaHTML;
}
?>