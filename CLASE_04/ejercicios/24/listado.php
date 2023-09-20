<?php
/**
 * Aplicación Nº 24 ( Listado JSON y array de usuarios)
 * 
 * Archivo: listado.php
 * 
 * método:GET
 * 
 * Recibe qué listado va a retornar(ej:usuarios,productos,vehículos,etc.),por ahora solo tenemos
 * usuarios).
 * En el caso de usuarios carga los datos del archivo usuarios.json.
 * se deben cargar los datos en un array de usuarios.
 * Retorna los datos que contiene ese array en una lista.
 * Hacer los métodos necesarios en la clase usuario
 * 
 * 
 * Bessio Rocio Soledad
 */

    require_once "Usuario.php";

    /**
     * #1: Me fijo que la request sea del tipo GET.
     * #2: Que este setteado el parametro listadoUsuarios.
     * #3: Me traigo el array de usuarios de mi fucnion estatica.
     * #4: Me fijo que ese array tenga algo dentro.
     * #6: Armo la cadena para imprimirla 
     */
    if($_SERVER['REQUEST_METHOD'] === 'GET'){//#1

        if(isset($_GET['listadoUsuarios'])){//#3
            
            $listadoUsuarios = $_GET['listadoUsuarios'];  

            if($listadoUsuarios === 'usuarios'){

                $usuarios = Usuario::ObtenerUsuarios('usuarios.json');//#4
    
                if(count($usuarios) > 0){//#5
                    //#6
                    echo '<ul>' . PHP_EOL;
    
                    foreach($usuarios as $usuario){
                        echo '<li>' . $usuario->getID() .' - '. $usuario->getNombre() . ' - '. $usuario->getMail() . ' - '. $usuario->getFechaRegistro() .'</li>';
                    }
                    
                    echo '</ul>' . PHP_EOL; 
                }
                else
                    echo "[No Hay nada dentro del archivo!]<br>"; 
            }
            else{
                echo "[Ocurrio un error al cargar los usuarios!]<br>"; 
            }
        }
    }
    else
        echo "[La request solicitada NO es del tipo GET. Reintente!]<br>"; 

?>