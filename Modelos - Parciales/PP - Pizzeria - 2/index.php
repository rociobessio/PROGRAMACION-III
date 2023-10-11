<?php

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        if (isset($_GET['accion'])) {
            switch ($_GET['accion']) { 
                case 'Consultar_Ventas':
                    require_once "PARTE03/ConsultasVentas.php";
                break;
                case 'Alta_Pizza':
                    require_once "PARTE01/PizzaCarga.php";
                break;
                default:
                    echo json_encode(['error' => 'Accion GET no permitido']);
                break;
            }
        } else {
            echo json_encode(['error' => 'Falta el parametro accion']);
        }
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_GET['accion'])) {
            switch ($_GET['accion']) {
                case 'Consultar_Pizza':
                    require_once "PARTE01/PizzaConsultar.php";
                break;
            case 'Alta_Pizza':
                    require_once "PARTE04/PizzaCarga.php";
                break;
                case 'Alta_Venta':
                    require_once "PARTE02/AltaVenta.php";
                break;
                default:
                    echo json_encode(['error' => 'Accion POST no permitido']);
                break;
            }
        } else {
            echo json_encode(['error' => 'Falta el parametro accion']);
        }
    } elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {

        if (isset($_GET['accion'])) {
            switch ($_GET['accion']) {
                case 'ModificarVenta':
                    include_once "PARTE04/ModificarVenta.php";
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
                    include_once "PARTE04/BorrarVenta.php";
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