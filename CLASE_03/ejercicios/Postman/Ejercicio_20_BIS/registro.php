<?php 
/**
 *  APLICACIÓN 21 - LISTADO CSV Y ARRAY DE USUARIOS
 * 
 *  Archivo: registro.php
 * 
 *  método:POST
 * 
 *  Recibe los datos del usuario(nombre, clave,mail )por POST ,
 *  crear un objeto y utilizar sus métodos para poder hacer el alta,
 *  guardando los datos en usuarios.csv.
 *  retorna si se pudo agregar o no.
 *  Cada usuario se agrega en un renglón diferente al anterior.
 *  Hacer los métodos necesarios en la clase usuario
 *  Hacer los métodos necesarios en la clase usuario
 * 
 *  Bessio Rocio Soledad
 */

    include_once 'usuarios.php';

    /**
     * #1: Verifico primeramente si la solicitud es del tipo POST
     * #2: Valido los datos entrantes.
     * #3: Cargo los datos ingresados.
     * #4: Verifico que NO esten vacios.
     * #5: filter_var funcion de PHP permite obtener un filtro especifico,
     *     en este caso utilizo FILTER_VALIDATE_EMAIL, constante predefinida
     *     que verificara si lo obtenido es un email.
     * #6: Llamo al metodo estatico y agrego en el csv al usuario
     */
    if($_SERVER['REQUEST_METHOD'] === 'POST'){//#1
        if(isset($_POST['nombre']) && isset($_POST['clave']) && isset($_POST['mail'])){//#2
            //#3
            $nombreUsuario = $_POST['nombre'];
            $claveUsuario = $_POST['clave'];
            $mailUsuario = $_POST['mail'];

            //#4
            if(empty($nombreUsuario) || empty($claveUsuario) || empty($mailUsuario))
                echo "<br>[Se deben de completar TODOS los campos!]</br>";
            else if(!filter_var($mailUsuario,FILTER_VALIDATE_EMAIL))//#5
                echo "<br>[La dirección de correo electronico ingresada es incorrecta!]</br>";
            else{
                //#6
                if(Usuario::GuardarUsuarioCSV(new Usuario($claveUsuario,$mailUsuario,$nombreUsuario))){
                    echo "[El usuario fue agregado correctamente!]<br>";
                }
                else{
                    echo "[No se pudo agregar al usuario!]<br>";
                }
            }
        }
    }
    else{
        echo "[La request solicitada NO es del tipo POST. Reintente!]<br>";
    }   
?>