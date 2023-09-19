<?php 
/** 
 * Aplicación No 23 (Registro JSON)
 * 
 * Archivo: registro.php
 * 
 * método:POST
 * 
 * Recibe los datos del usuario(nombre, clave,mail )por POST ,
 * crea un ID autoincremental(emulado, puede ser un random de 1 a 10.000). crear un dato con la
 * fecha de registro , toma todos los datos y utilizar sus métodos para poder hacer el alta,
 * guardando los datos en usuarios.json y subir la imagen al servidor en la carpeta
 * Usuario/Fotos/.
 * retorna si se pudo agregar o no.
 * Cada usuario se agrega en un renglón diferente al anterior.
 * Hacer los métodos necesarios en la clase usuario.
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
     * #6: Veo de dar de alta al usuario y guardarlo en archivo .json
     * 
     * #7: Creo el path completo de la imagen!
     *     Paso la ruta temporal.
     */
    if($_SERVER['REQUEST_METHOD'] === 'POST'){//#1
        if(isset($_POST['nombre']) && isset($_POST['clave']) && isset($_POST['mail']) && isset($_FILES['imagen'])){//#2
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

                //#7
                $url_destino = "..\\23\\Usuario\\Fotos\\";
                $imgUsuario = $_FILES['imagen']['name'];
                $url_imagen = $url_destino . $imgUsuario;

                //#6
                if(Usuario::AltaUsuarios(new Usuario($claveUsuario,$mailUsuario,$nombreUsuario,$url_imagen))){
                    echo "[Usuario registrado!]";

                    if (move_uploaded_file($_FILES["imagen"]["tmp_name"], $url_imagen)) {
                        echo "[Imagen guardada!]";
                    }
                    else
                        echo "[NO se pudo guardar la imagen!]";
                }
                else
                    echo "[Algo salio mal al intentar guardar al usuario!]" ;
            }
        }
    }
    else{
        echo "[La request solicitada NO es del tipo POST. Reintente!]<br>";
    }   
?>