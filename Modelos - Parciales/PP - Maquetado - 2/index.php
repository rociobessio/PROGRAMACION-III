<?php

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        if (isset($_GET['accion'])) {
            switch ($_GET['accion']) { 
                case 'ConsultarVentas':
                    require_once "aplicacion/ConsultarVentas.php";
                break;
                case 'Alta_Producto':
                    require_once "aplicacion/AltaProductoGet.php";
                break;
                default:
                    echo json_encode(['error' => 'Accion GET no permitida']);
                break;
                case 'ConsultarDevoluciones':
                    require_once "aplicacion/ConsultarDevoluciones.php";
                break; 
            }
        } else {
            echo json_encode(['ERROR' => 'Falta el parametro accion']);
        }
    }elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_GET['accion'])) {
            switch ($_GET['accion']) {
                case 'Consultar_Producto':
                    require_once "aplicacion/ProductoConsultar.php";
                break;
                case 'Alta_Producto':
                    require_once "aplicacion/ProductoCargarPOST.php";
                break;
                case 'AltaVenta':
                    require_once "aplicacion/AltaVenta_2.php";
                break;
                case 'DevolverProducto':
                    require_once "aplicacion/DevolverProducto.php";
                break;
                default:
                    echo json_encode(['ERROR' => 'Accion POST no permitido']);
                break;
            }
        } else {
            echo json_encode(['ERROR' => 'Falta el parametro accion']);
        }
    } elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {

        if (isset($_GET['accion'])) {
            switch ($_GET['accion']) {
                case 'ModificarVenta':
                    include_once "aplicacion/ModificarVenta.php";
                break;
                default:
                    echo json_encode(['error' => 'Accion PUT no valida']);
                break;
            }
        } else {
            echo json_encode(['error' => 'Falta el parametro accion']);
        }
    } elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {

        if (isset($_GET['accion'])) {
            switch ($_GET['accion']) {
                case 'EliminarVenta':
                    include_once "aplicacion/BorrarVenta.php";
                break;
                default:
                    echo json_encode(['error' => 'Accion DELETE no valida']);
                break;
            }
        } else {
            echo json_encode(['error' => 'Falta el parametro accion']);
        }
    } else {
        echo json_encode(['error' => 'Metodo HTTP no permitido']);
    }
    