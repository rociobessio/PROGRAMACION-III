<?php
/**
 *  APLICACIÓN 22 - LOGIN
 * 
 * Archivo: login.php
 * 
 * Método: POST
 * 
 * Enunciado:
 * Recibe los datos del usuario(clave,mail )por POST ,
 * crear un objeto y utilizar sus métodos para poder verificar si es un usuario registrado, Retorna
 * un :
 * “Verificado” si el usuario existe y coincide la clave también.
 * “Error en los datos” si esta mal la clave.
 * “Usuario no registrado si no coincide el mail“
 * Hacer los métodos necesarios en la clase usuario.
 * 
 * Bessio Rocio Soledad
 */

    include_once 'usuarios.php';

    /**
     * Recibe los datos del usuario(clave,mail ) por POST,
     * crear un objeto y utilizar sus métodos para poder verificar si es un usuario registrado.
     * 
     * #1: Primero verifico que la request sea del tipo POST.
     * #2: Verifico que no falten datos necesarios.
     * #3: Creo el usuario y me fijo si esta registrado
     */
    if($_SERVER['REQUEST_METHOD'] === 'POST'){//#1
        if(isset($_POST['clave']) && isset($_POST['mail'])){//#2
            //#3
            $clave = $_POST['clave'];
            $mail = $_POST['mail'];
            $usuarioRegistrado = new Usuario($mail,$clave);
            
            $resultado = $usuarioRegistrado->Verificar($mail,$clave);

            echo $resultado;
        }
        else
            echo "[Se necesitan todos los datos!]";
    }
    else
        echo "[La request solicitada NO es del tipo POST. Reintente!]<br>";
?>