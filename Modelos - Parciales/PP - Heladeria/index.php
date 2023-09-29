<?php

/**
 * index.php:Recibe todas las peticiones que realiza el postman, y administra a qué archivo se debe incluir.
 */

 switch($_SERVER['REQUEST_METHOD']){
    case 'GET':
        if(isset($_GET['accion'])){
            switch ($_GET['accion']) {
                case 'funcion':
                    //Incluir clases
                    require_once "Heladeria.php";

                    // if(isset($_GET[])){

                    // }
                break;
            }
        }
    break;

    case 'POST':
        if(isset($_POST['accion'])){
            switch ($_POST['accion']) {
                case 'Alta_Helado':
                    require_once "HeladeriaAlta.php";
                break;
                case 'Consultar_Helado':
                    require_once "HeladoConsultar.php";
                break;
                case 'Alta_Venta':
                    require_once "AltaVenta.php";
                break;
                case 'Devolver_Helado':
                    require_once "DevolverHelado.php";
                break;
                default:
                    echo "[Acción POST NO VALIDA!]";
                break;
            }
        }
    break;
    case 'PUT':
        if(isset($_PUT['accion'])){
            switch($_PUT['accion']){
                case 'Modificar_Venta':
                    require_once "ModificarVenta.php";
                break;
            }
        }
    break;
    default:
        echo "[La request solicitada no esta permitida.]" . '<br>';
    break;
 }