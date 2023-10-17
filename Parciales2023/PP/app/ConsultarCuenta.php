<?php

    require_once "./classes/Cuenta.php";

    /**
     * ConsultarCuenta.php: (por POST) Se ingresa Tipo y Nro. de Cuenta, si coincide con
     * algún registro del archivo banco.json, retornar la moneda/s y saldo de la cuenta/s. De
     * lo contrario informar si no existe la combinación de nro y tipo de cuenta o, si existe el
     * número y no el tipo para dicho número, el mensaje: “tipo de cuenta incorrecto”.
     */
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        if(isset($_POST['numeroCuenta']) && isset($_POST['tipoCuenta'])){
            $numeroCuenta = intval($_POST['numeroCuenta']);
            $tipo = $_POST['tipoCuenta'];

            $jsonFilename = './archivos/banco.json';
            $cuentas = Cuenta::leerJSON($jsonFilename);

            $mensaje = Cuenta::buscarCuentaPor($cuentas,$numeroCuenta,$tipo);
            echo json_encode(['resultado' => $mensaje]);
        }
        else
            echo json_encode(['ERROR' => 'Faltan parametros por ingresar!']);
    }