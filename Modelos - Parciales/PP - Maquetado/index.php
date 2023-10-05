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
                    require_once "aplicacion/ConsultasVentas.php";
                break;
            }
        }
    break;

    case 'POST':
        if(isset($_POST['accion'])){
            switch ($_POST['accion']) {
                case 'Alta_Producto':
                    require_once "aplicacion/ProductosCarga.php";
                break;
                case 'Consultar_Producto':
                    require_once "aplicacion/ProductoConsultar.php";
                break;
                case 'Alta_Venta':
                    require_once "aplicacion/AltaVenta.php";
                break;
                case 'Devolver_Producto':
                    require_once "aplicacion/DevolverProducto.php";
                break;
            }
        }
    break;
    default:
        echo "[La request solicitada no esta permitida.]" . '<br>';
    break;
 }