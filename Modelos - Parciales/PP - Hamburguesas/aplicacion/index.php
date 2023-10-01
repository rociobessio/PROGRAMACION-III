<?php
/**
 * Parte 01
 * (1 pt.) index.php:
 * Recibe todas las peticiones que realiza el postman, y administra a qué archivo se debe incluir.
 */
switch($_SERVER['REQUEST_METHOD']){
    case 'GET':
        if(isset($_GET['accion'])){
            switch ($_GET['accion']) {
                case 'Consultar_Ventas':
                    require_once "../aplicacion/ConsultasVentas.php";
                break;
            }
        }
    break;

    case 'POST':
        if(isset($_POST['accion'])){
            switch ($_POST['accion']) {
                case 'Alta_Hamburguesa':
                    require_once "../aplicacion/HamburguesaCarga.php";
                break;
                case 'Consultar_Hamburguesa':
                    require_once "../aplicacion/HamburguesasConsultar.php";
                break;
                case 'Alta_Venta':
                    require_once "../aplicacion/AltaVenta.php";
                break;
                case 'Devolver_Hamburguesa':
                    require_once "../aplicacion/DevolverHamburguesa.php";
                break;
            }
        }
    break;
    default:
        echo "[La request solicitada no esta permitida.]" . '<br>';
    break;
 }