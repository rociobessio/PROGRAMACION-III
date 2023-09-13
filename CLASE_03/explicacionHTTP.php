<?php
/**
 *                   Protocolo HTTP
 * Permite comunicacion entre cliente (envia la peticio) y servidor (genera la respuesta).
 * 
 * 
 */
    var_dump($_GET);

    echo "<br/>Hola " . $_GET['nombre'] . " " . $_GET['apellido'];

    var_dump($_REQUEST);
?>