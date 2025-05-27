<?php
/**
* Esta clase centralizará la implementación de operaciones comunes en los servicios.
* Se describirán una a una.
*/
class Utilities {

    /**
    * Función para validar obligatoriedad de parámetros.
    * @arg params Todos los parámetros recibidos en el GET
    * @arg paramName Nombre del parámetro a chequear.
    * @return valor del parámetro validado.
    */
    public static function validateMandatoryParameter($params, $paramName) {
        if (!isset($params[$paramName]) || $params[$paramName] === '') {
            throw new Exception("Parametro Obligatorio: '$paramName'");
        }

        return $params[$paramName];
    }

    /**
    * Función para generar el XML de respuesta (usado en respuesta errónea).
    * @arg status Estado a incluir en el XML.
    * @arg description Descripción de la información a incluir en el XML.
    * @return XML generado.
    */
    public static function generateResponseXML($status, $description) {
        $responseXML = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><response></response>');
        $responseXML->addChild('status', $status);
        $responseXML->addChild('description', $description);

        return $responseXML;
    }

    /**
    * Función para formatear fecha desde el formato europeo hasta el usado en la BD MySQL.
    * @arg date String con la fecha a formatera (DD/MM/YYYY)
    * @return String con la fecha validada y formateada (YYYY-MM-DD)
    */
    public static function formatEuropeanDateToMySQL($date) {
        // Formato de entrada: DD/MM/YYYY
        $dateParts = explode('/', $date);
        if (count($dateParts) === 3) {
            // Formato de salida: YYYY-MM-DD
            return $dateParts[2] . '-' . $dateParts[1] . '-' . $dateParts[0];
        } else {
            throw new Exception("Invalid date format: $date");
        }
    }

        /**
    * Función para generar una apikey de tamaño 10 caracteres.
    * @return String apikey generada aleatoriamente gracias 
    * al método str_shuffle entre números del 1-9 y letras de la A-Z mayúsculas y minúsculas
    */
    public static function generateRandomApikey(){

        $cPermitidos='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        //mete una clave con los caracteres y lo repite x veces.
        $apikey=substr(str_shuffle($cPermitidos),0,10);
        return $apikey;
    }

    public static function checkApikey($conn,$apikey){
        $apikeyValido=false;

        $select = $conn->prepare("SELECT userapplicationid FROM userapplication WHERE apikey=? ");
        $select -> bind_param("s",$apikey);
        $select->execute();
        $result = $select-> get_result();

        if($result->num_rows>0) {
            while($row = $result->fetch_assoc()){
                $apikeyValido=true;
            }
        }

        return $apikeyValido;
    }

    public static function obtenerDniUsuario($conn, $apikey) {
        $dni = null;
    
        $select = $conn->prepare("SELECT dni FROM userapplication WHERE apikey = ?");
        $select->bind_param("s", $apikey);
        $select->execute();
        $result = $select->get_result();
    
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $dni = $row['dni'];
        }
    
        return $dni;
    }

    public static function obtenerVINConMatricula($conn, $matricula) {
        $vin = null;
    
        $select = $conn->prepare("SELECT vin FROM vehiculo WHERE matricula = ?");
        $select->bind_param("s", $matricula);
        $select->execute();
        $result = $select->get_result();
    
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $vin = $row['vin'];
        }
    
        return $vin;
    }

    public static function generarMatricula() {
        // Generar número entre 0000 y 9999 con padding
        $numero = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
    
        // Letras permitidas (sin vocales para evitar palabras ofensivas)
        $letrasPermitidas = 'BCDFGHJKLMNPQRSTVWXYZ';
    
        // Generar tres letras aleatorias
        $letras = '';
        for ($i = 0; $i < 3; $i++) {
            $letras .= $letrasPermitidas[rand(0, strlen($letrasPermitidas) - 1)];
        }
    
        // Concatenar número y letras
        return $numero . $letras;
    }

    public static function esAdministrador($conn, $dniUsuario) {
        $esAdmin = false;
    
        $select = $conn->prepare(" SELECT r.nombreRol FROM Usuario u JOIN Rol r ON u.idRol = r.idRol WHERE u.DNIUsuario = ?");
        $select->bind_param("s", $dniUsuario);
        $select->execute();
        $result = $select->get_result();
    
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (strtolower($row['rol']) === 'admin') {
                $esAdmin = true;
            }
        }
    
        return $esAdmin;
    }
    
}
?>