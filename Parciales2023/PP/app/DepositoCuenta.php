<?php
    require_once "./classes/Cuenta.php";
    require_once "./classes/Deposito.php";

    /**
     * a- DepositoCuenta.php: (por POST) se recibe el Tipo de Cuenta, Nro de Cuenta y
     * Moneda y el importe a depositar, si la cuenta existe en banco.json, se incrementa el
     * saldo existente según el importe depositado y se registra en el archivo depósitos.json
     * la operación con los datos de la cuenta y el depósito (fecha, monto) e id
     * autoincremental). Si la cuenta no existe, informar el error.
     * 
     * b- Completar el depósito con imagen del talón de depósito con el nombre: Tipo de
     * Cuenta, Nro. de Cuenta e Id de Depósito, guardando la imagen en la carpeta
     * /ImagenesDeDepositos2023.
     */
    if($_SERVER['REQUEST_METHOD'] === 'POST'){

        if(isset($_POST['tipoCuenta']) && isset($_POST['nroCuenta']) && isset($_POST['moneda'])
        && isset($_POST['importe']) && isset($_FILES['imagen'])){
            $imagen = $_FILES['imagen'];
            $moneda = $_POST['moneda'];
            $nroCuenta = intval($_POST['nroCuenta']);
            $importe = floatval($_POST['importe']);
            $tipoCuenta = $_POST['tipoCuenta'];
            
            $jsonFileCuentas = './archivos/banco.json';
            $cuentas = Cuenta::leerJSON($jsonFileCuentas);
            $cuenta = Cuenta::buscarPorNumeroCuenta($cuentas,$nroCuenta,$tipoCuenta,$moneda); 

            if(Deposito::generarDeposito($cuenta,$cuentas,$importe,$moneda,$imagen)){

                echo json_encode(['SUCCESS' => 'Deposito generado correctamente!<br>']);
            }
            else
                echo json_encode(['ERROR' => 'No se pudo generar el desposito!']);
        }
        else
            echo json_encode(['ERROR' => 'Se necesitan todos los datos para seguir!']);
    }