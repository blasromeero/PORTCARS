<?php
require '../../SQL/db.php';
require 'utilidades.php';

try {
    $vinVehiculo = Utilities::validateMandatoryParameter($_GET, 'vinVehiculo');
    $paisOrigen = Utilities::validateMandatoryParameter($_GET, 'paisOrigen');
    $nombreModelo = Utilities::validateMandatoryParameter($_GET, 'nombreModelo');
    $apikey = Utilities::validateMandatoryParameter($_GET, 'apikey');


    $database = new Database();
    $conn = $database->getConnection();

    $apikeyValida = Utilities::checkApikey($conn,$apikey);
    $dniUsuario = Utilities::obtenerDniUsuario($conn,$apikey);

        if($apikeyValida === True){
            $sql = $conn->prepare("INSERT INTO Importacion (fechaImportacion, pais, estado, DNIUsuario, VIN) VALUES (CURDATE(), ?,'Pendiente',?,?)");
            $sql->bind_param("sss", $paisDestino,$dniUsuario,$vinVehiculo);
        
            if ($sql->execute()) {
                if ($sql->affected_rows > 0) {

                    $matriculaVehiculo = Utilities::generarMatricula();

                    $sql = $conn->prepare("INSERT INTO Vehiculo (VIN, matricula, fechaObtencion, DNIUsuario, nombreModelo) VALUES (?,?,CURDATE(),?,?);");
                    $sql->bind_param("ssss",$vinVehiculo, $matriculaVehiculo,$dniUsuario,$nombreModelo);

                    if ($sql->execute()) {
                        if ($sql->affected_rows > 0) {
                            $respuestaHTML = "<p>Se ha iniciado el proceso de importacion del vehiculo correctamente! Gracias por usar PORTCARS</p>";
                    echo $respuestaHTML;
                        } else {
                            $respuestaHTML = "<p>No se ha podido hacer la exportacion, contacte con servicio tecnico</p>";
                            echo $respuestaHTML;
                        }    
                    } else {
                        throw new Exception("El vehiculo no se ha podido insertar de la base de datos, consulte con servicio tecnico.");
                    }
                } else {
                    $respuestaHTML = "<p>No se ha podido hacer la importacion, contacte con servicio tecnico</p>";
                    echo $respuestaHTML;
                }    
            } else {
                throw new Exception("Error al insertar la importacion en la base de datos.");
            }
        } else {
            throw new Exception("La APIKEY del usuario no es valida!");
        }

    $sql->close();
} catch (Exception $e) {
    $respuestaHTML = "<p>ERROR AL REALIZAR UNA EXPORTACIOn: {$e->getMessage()} </p>";
    echo $respuestaHTML;
}
?>