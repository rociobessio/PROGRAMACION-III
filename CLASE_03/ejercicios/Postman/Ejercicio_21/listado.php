<?php
/**
 * APLICACIÓN 21 - LISTADO CSV Y ARRAY DE USUARIOS
 * 
 * Archivo: listado.php
 * 
 * Método: GET
 * 
 * Enunciado:
 * Recibe qué listado va a retornar(ej:usuarios,productos,vehículos,...etc),por ahora solo tenemos
 * usuarios).
 * En el caso de usuarios carga los datos del archivo usuarios.csv.
 * se deben cargar los datos en un array de usuarios.
 * Retorna los datos que contiene ese array en una lista

 * <ul>
 * <li>Coffee</li>
 * <li>Tea</li>
 * <li>Milk</li>
 * </ul>
 * Hacer los métodos necesarios en la clase usuario
 * 
 * 
 * Bessio Rocio Soledad.
 */

    require_once "usuarios.php";
    /**
     * #1: Verifico primeramente si la solicitud es del tipo GET.
     * #2: Recibo el listado.
     * #3: Valido los datos entrantes.
     * #4: Cargo en el array los usuarios del csv.
     * #5: Verifico que el array este cargado.
     * #6: Recorro la lista y formo el listado de usuarios.
     */
    if($_SERVER['REQUEST_METHOD'] === 'GET'){//#1

        if(isset($_GET['listadoUsuarios'])){//#3
            
            $listadoUsuarios = $_GET['listadoUsuarios'];  

            if($listadoUsuarios === 'usuarios'){
                $usuarios = Usuario::CargarUsuariosCSV('usuarios.csv');//#4
    
                if(count($usuarios) > 0){//#5
                    //#6
                    echo '<ul>' . PHP_EOL;
    
                    foreach($usuarios as $usuario){
                        echo '<li>' . $usuario->getNombre() . ' - '. $usuario->getMail() . '</li>';
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