<?php

    /**
     * Aplicación No 29( Login con bd)
     * Archivo: Login.php
     * método:POST
     * Recibe los datos del usuario(clave,mail )por POST ,
     * crear un objeto y utilizar sus métodos para poder verificar si es un usuario registrado en la
     * base de datos,
     * Retorna un :
     * “Verificado” si el usuario existe y coincide la clave también.
     * “Error en los datos” si esta mal la clave.
     * “Usuario no registrado si no coincide el mail“
     * Hacer los métodos necesarios en la clase usuario.
     */

    include_once "./33/controllers/usuarioController.php";

    if(isset($_GET['email']) && isset($_GET['clave'])){
        $usuarioController = new usuarioController();
        echo $usuarioController->loginUsuario($_GET['email'],$_GET['clave']);
    }
    else
        echo json_encode(['error' => 'Faltan parametros por ingresar']);