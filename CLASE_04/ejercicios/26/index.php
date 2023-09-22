<?php
/**
 * Aplicación Nº 26 (RealizarVenta)
 * 
 * Archivo: RealizarVenta.php
 * 
 * método:POST
 * 
 * Recibe los datos del producto(código de barra), del usuario (el id )y la cantidad de ítems ,por
 * POST .
 * Verificar que el usuario y el producto exista y tenga stock.
 * crea un ID autoincremental(emulado, puede ser un random de 1 a 10.000). carga
 * los datos necesarios para guardar la venta en un nuevo renglón.
 * Retorna un :
 * “venta realizada”Se hizo una venta
 * “no se pudo hacer“si no se pudo hacer
 * Hacer los métodos necesaris en las clases
 * 
 * Bessio Rocio Soledad
 */

    /**
     * Aplico "enrutamiento"
     */
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'GET':
            if (isset($_GET['accion'])) {
                switch ($_GET['accion']) {
                    case 'leer':
                        require_once "Productos.php";
                        require_once "Usuarios.php";
                    break;
                }
            }
        break;
    
        case 'POST':
            if (isset($_POST['accion'])) {
                switch ($_POST['accion']) {
                    case 'RealizarVenta':
                        require_once "Productos.php";
                        require_once "Usuarios.php";
    
                        if (isset($_POST['codBarra']) && isset($_POST['idUsuario']) && isset($_POST['cantidad'])) {
                            // Aquí puedes poner el código para realizar la venta.
                        } else {
                            echo "[Se necesitan todos los datos!]";
                        }
                    break;
                }
            } 
            else {
                echo "[Falta el parámetro 'accion' en POST]";
            }
         break;

        default:
            echo "[La request solicitada NO está permitida. Reintente!]<br>";
        break;
    }
    

?>