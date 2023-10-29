<?php

    include_once "./27/controllers/usuarioController.php";

    /**
     * Ejercicio 27 - RegistroBD
     * Recibe los datos del usuario( nombre,apellido, clave,mail,localidad )por POST , crear
     * un objeto con la fecha de registro y utilizar sus métodos para poder hacer el alta,
     * guardando los datos la base de datos
     * retorna si se pudo agregar o no.
     * 
     * Bessio Rocio Soledad
     */
    if(isset($_POST['nombre']) && isset($_POST['apellido']) && isset($_POST['clave']) &&
       isset($_POST['mail']) && isset($_POST['localidad'])){
        $usuarioController = new usuarioController();
        $resultado = $usuarioController->registrarUsuario($_POST['nombre'],$_POST['apellido'],$_POST['clave'],
                                                          $_POST['mail'],$_POST['localidad']);
        echo $resultado ? json_encode(['SUCCESS' => 'Usuario registrado correctamente!<br>']) :
                          json_encode(['ERROR' => 'No se pudo registrar el usuario<br>']);
    }
    else
        echo json_encode(['error' => 'Faltan parametros por ingresar!<br>']);