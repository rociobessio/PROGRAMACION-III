<?php
    /**
     * A- index.php: Recibe todas las peticiones que realiza el cliente (utilizaremos Postman),
     * y administra a qué archivo se debe incluir.
     */
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        if (isset($_GET['accion'])) {
            switch ($_GET['accion']) { 
                case 'ConsultarMovimientos':
                    include_once "app/ConsultaMovimientos.php";
                break;
                default:
                    echo json_encode(['error' => 'Accion GET no permitida']);
                break;
            }
        } else {
            echo json_encode(['ERROR' => 'Falta el parametro accion']);
        }
    }
    elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_GET['accion'])) {
            switch ($_GET['accion']) {
                case 'CuentaAlta':
                    include_once "app/CuentaAlta.php";
                break;
                case 'ConsultarCuenta':
                    include_once "app/ConsultarCuenta.php";
                break;
                case 'DepositoCuenta':
                    include_once "app/DepositoCuenta.php";
                break;
                case 'RetiroCuenta':
                    include_once "app/RetiroCuenta.php";
                break;
                case 'AjusteCuenta':
                    include_once "app/AjusteCuenta.php";
                break;
                default:
                    echo json_encode(['ERROR' => 'Accion POST no permitido']);
                break;
            }
        } else {
            echo json_encode(['ERROR' => 'Falta el parametro accion']);
        }
    }
    elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {

        if (isset($_GET['accion'])) {
            switch ($_GET['accion']) {
                case 'ModificarCuenta':
                    include_once "app/ModificarCuenta.php";
                break;
                default:
                    echo json_encode(['error' => 'Accion PUT no valida']);
                break;
            }
        } else {
            echo json_encode(['error' => 'Falta el parametro accion']);
        }
    } 
    elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {

        if (isset($_GET['accion'])) {
            switch ($_GET['accion']) {

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