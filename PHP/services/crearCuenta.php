<?php
require '../../SQL/db.php';
require 'utilidades.php';

try {
    $dniUsuario = Utilities::validateMandatoryParameter($_GET, 'dni');
    $username = Utilities::validateMandatoryParameter($_GET, 'username');
    $password = Utilities::validateMandatoryParameter($_GET, 'contraseña');
    $nombreUsuario = Utilities::validateMandatoryParameter($_GET, 'nombre');
    $apellido1 = Utilities::validateMandatoryParameter($_GET, 'apellido1');
    $apellido2 = Utilities::validateMandatoryParameter($_GET, 'apellido2');
    $direccion = Utilities::validateMandatoryParameter($_GET, 'direccion');
    $numeroTelefono = Utilities::validateMandatoryParameter($_GET, 'numeroTelefono');
    $apikey = Utilities::generateRandomApikey();


    $database = new Database();
    $conn = $database->getConnection();

    $sql = $conn->prepare("INSERT INTO usuario (DNIUsuario, username, password, nombre, apellido1, apellido2, direccion, numeroTelefono, apikey) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $sql->bind_param("sssssssss", $dniUsuario ,$username, $password,$nombreUsuario,$apellido1,$apellido2,$direccion,$numeroTelefono,$apikey);

    if ($sql->execute()) {
        if ($sql->affected_rows > 0) {
            $respuestaHTML = "<p>Te has registrado correctamente en PORTCARS</p>";
            echo $respuestaHTML;
        } else {
            $respuestaHTML = "<p>El DNI con el que intentas registrarse ya esta en la base de datos!</p>";
            echo $respuestaHTML;
        }    
    } else {
        throw new Exception("El DNI con el que intentas registrarse ya esta en la base de datos!");
    }

    $sql->close();
} catch (Exception $e) {
    $respuestaHTML = "<p>ERROR AL INSERTAR EL USUARIO: {$e->getMessage()} </p>";
    echo $respuestaHTML;
}
?>