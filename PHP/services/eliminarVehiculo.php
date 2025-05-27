<?php
require '../../SQL/db.php';
require 'utilidades.php';

try {
    $matriculaVehiculo = Utilities::validateMandatoryParameter($_GET, 'matricula');
    $apikey = Utilities::validateMandatoryParameter($_GET, 'apikey');


    $database = new Database();
    $conn = $database->getConnection();

    $apikeyValida = Utilities::checkApikey($conn,$apikey);

    if($apikeyValida === True){
        $sql = $conn->prepare("DELETE FROM Vehiculo WHERE matricula = ?");
        $sql->bind_param("s", $matriculaVehiculo);
    
        if ($sql->execute()) {
            if ($sql->affected_rows > 0) {
                $respuestaHTML = "<p>El vehiculo se ha eliminado correctamente!</p>";
                echo $respuestaHTML;
            } else {
                $respuestaHTML = "<p>El Vehiculo no existe en la base de datos! Prueba con otra matricula!</p>";
                echo $respuestaHTML;
            }    
        } else {
            throw new Exception("El vehiculo no se ha podido eliminar de la base de datos, consulte con servicio tecnico.");
        }
    } else {
        throw new Exception("La APIKEY del usuario no es valida!");
    }


    $sql->close();
} catch (Exception $e) {
    $respuestaHTML = "<p>ERROR AL BORRAR UN VEHICULO: {$e->getMessage()} </p>";
    echo $respuestaHTML;
}
?>