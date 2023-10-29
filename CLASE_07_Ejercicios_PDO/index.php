<?php
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        if (isset($_GET['accion'])) {
            switch ($_GET['accion']) {
                case 'listarUsuarios':
                    include_once "28/aplicacion/listado.php";
                break;
                case 'loginUsuario':
                    include_once "29/aplicacion/login.php";
                break;
                default:
                    echo json_encode(['error' => 'Accion GET no valida']);
                break;
            }
        }
        else {
            echo json_encode(['error' => 'Falta el parametro accion']);
        }
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_GET['accion'])) {
            switch ($_GET['accion']) {
                case 'RegistrarUsuario':
                    include_once "27/aplicacion/registro.php";
                break;
                case 'ModificarUsuario':
                    include_once "32/app/modificacionUsuario.php";
                break;
                case 'AltaProducto':
                    include_once "30/app/altaProducto.php";
                break;
                case 'RealizarVenta':
                    include_once "31/app/realizarVenta.php";
                break;
                case 'ModificarProducto':
                    include_once "33/app/modificarProducto.php";
                break;
                default:
                    echo json_encode(['error' => 'Accion POST no valida']);
                break;
            }
        }else {
            echo json_encode(['error' => 'Falta el parametro accion']);
        }
    }