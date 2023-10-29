<?php

    /**
     * Aplicación No 32(Modificacion BD)
     * Archivo: ModificacionUsuario.php
     * método:POST
     * Recibe los datos del usuario(nombre, clavenueva, clavevieja,mail )por POST
     * , crear un objeto y utilizar sus métodos para poder hacer la modificación,
     * guardando los datos la base de datos
     * retorna si se pudo agregar o no.
     * Solo pueden cambiar la clave
     * 
     * Bessio Rocio Soledad
     */

    include_once "./33/controllers/usuarioController.php";

    if(isset($_POST['claveNueva']) && isset($_POST['claveVieja']) &&
       isset($_POST['mail'])){
        $usuarioController = new UsuarioController();
        $resultado = $usuarioController->modificarUsuario($_POST['mail'],$_POST['claveNueva'],$_POST['claveVieja']);
        echo $resultado ? json_encode(['SUCCESS' => 'Se ha modificado la clave del usuario!']) :
            json_encode(['ERROR' => 'No se ha podido modificar la clave del usuario!']);
    }
    else
        echo json_encode(['error' => 'Faltan el parametros por ingresar!']);