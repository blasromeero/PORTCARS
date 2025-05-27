<?php
require '../../SQL/db.php';
require 'utilidades.php';

try {
    $dniUsuario = Utilities::validateMandatoryParameter($_GET, 'dni');
    $apikey = Utilities::validateMandatoryParameter($_GET, 'apikey');


    $database = new Database();
    $conn = $database->getConnection();

    $apikeyValida = Utilities::checkApikey($conn,$apikey);

    if($apikeyValida === True){
        $sql = $conn->prepare("INSERT INTO Rol_Usuario (IDRol, DNIUsuario) VALUES (?, ?)");
        $sql->bind_param("is", '0' ,$dniUsuario);
    
        if ($sql->execute()) {
            if ($sql->affected_rows > 0) {
                $respuestaHTML = "<p>Se te ha verificado en PORTCARS, ya puedes empezar a utilizarlo!</p>";
                echo $respuestaHTML;
            } else {
                $respuestaHTML = "<p>Ha ocurrido un error a la hora de verificarte, por favor contacte con servicio tecnico</p>";
                echo $respuestaHTML;
            }    
        } else {
            throw new Exception("Este usuario tiene ya el rol de usuario.");
        }
    } else {
        throw new Exception("La APIKEY del usuario no es valida!");
    }


    $sql->close();
} catch (Exception $e) {
    $respuestaHTML = "<p>ERROR AL INSERTAR EL ROL AL USUARIO: {$e->getMessage()} </p>";
    echo $respuestaHTML;
}
?>