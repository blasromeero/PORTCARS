<?php
require '../../SQL/db.php';
require 'utilidades.php';

try {
    $username = Utilities::validateMandatoryParameter($_GET, 'usuario');
    $password = Utilities::validateMandatoryParameter($_GET, 'contraseña');

    $database = new Database();
    $conn = $database->getConnection();

    
    $sql = $conn->prepare("SELECT apikey FROM Usuario WHERE username = ? AND password = ?");
    $sql->bind_param("ss", $username, $password);
    
    if ($sql->execute()) {
        $result = $sql->get_result();
    
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $apikey = $row['apikey'];
            echo "<p>$apikey</p>";
        } else {
            echo "<p>Error al iniciar sesión, has introducido mal el usuario o la contraseña.</p>";
        }
    } else {
        throw new Exception("Error al consultar el usuario. Consulte con el servicio técnico.");
    }

    $sql->close();
} catch (Exception $e) {
    $respuestaHTML = "<p>ERROR AL INICIAR SESION: {$e->getMessage()} </p>";
    echo $respuestaHTML;
}
?>