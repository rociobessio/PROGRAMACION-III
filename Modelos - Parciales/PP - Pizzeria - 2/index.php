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
                    require_once "PARTE01/ConsultasVentas.php";
                break;
                case 'Alta_Pizza':
                    require_once "PARTE01/PizzaCarga.php";
                break;
                default:
                    echo json_encode(['error' => 'Metodo GET no permitido']);
                break;
            }
        }
    break;

    case 'POST':
        if(isset($_POST['accion'])){
            switch ($_POST['accion']) {
                case 'Consultar_Pizza':
                    require_once "PARTE01/PizzaConsultar.php";
                break;
                case 'Alta_Venta':
                    require_once "PARTE02/AltaVenta.php";
                break;
                case 'Devolver_Producto':
                    require_once "aplicacion/DevolverProducto.php";
                break;
                default:
                    echo json_encode(['error' => 'Metodo POST no permitido']);
                break;
            }
        }
    break;

    case 'PUT':
        if(isset($_POST['accion'])){
            switch ($_POST['accion']) {
                case 'Modificar_Producto':

                break;
                default:
                    echo json_encode(['error' => 'Metodo PUT no permitido']);
                break;
            }
        }
    break;

    case 'DELETE':
        if(isset($_POST['accion'])){
            switch ($_POST['accion']) {
                case 'Eliminar_Producto':

                break;
                default:
                    echo json_encode(['error' => 'Metodo DELETE no permitido']);
                break;
            }
        }
    break;
    //-->Devuelvo un estandar
    default:
        echo json_encode(['error' => 'Metodo HTTP no permitido']);
    break;
 }