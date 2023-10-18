<?php

    require_once "./classes/Cuenta.php";
    require_once "./classes/Retiro.php";

    /**
     * 6- RetiroCuenta.php: (por POST) se recibe el Tipo de Cuenta, Nro de Cuenta y Moneda
     * y el importe a depositar, si la cuenta existe en banco.json, se decrementa el saldo
     * existente según el importe extraído y se registra en el archivo retiro.json la operación
     * con los datos de la cuenta y el depósito (fecha, monto) e id autoincremental.
     * Si la cuenta no existe o el saldo es inferior al monto a retirar, informar el tipo de error.
    */
    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        if(isset($_POST['tipoCuenta']) && isset($_POST['numeroCuenta']) && isset($_POST['moneda']) &&
        isset($_POST['importe'])){
            $numeroCuenta = intval($_POST['numeroCuenta']);
            $tipoCuenta = $_POST['tipoCuenta'];
            $moneda = $_POST['moneda'];
            $importeRetiro = floatval($_POST['importe']);

            $cuentas = Cuenta::leerJSON('./archivos/banco.json');
            $cuenta = Cuenta::buscarPorNumeroCuenta($cuentas,$numeroCuenta,$tipoCuenta,$moneda);

            if(Retiro::generarRetiro($cuenta,$importeRetiro,$cuentas)){
                echo json_encode(['SUCCESS' => 'Extraccion generada correctamente!<br>']);
            }
            else
                echo json_encode(['ERROR' => 'No se ha podido generar la extraccion!<br>']);
        }
        else
            echo json_encode(['ERROR' => 'Se necesitan todos los parametros para seguir!<br>']);
    }