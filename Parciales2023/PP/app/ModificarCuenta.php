<?php
    require_once "./classes/Cuenta.php";
    /**
     * 5- ModificarCuenta.php (por PUT)
     * Debe recibir todos los datos propios de una cuenta (a excepción del saldo); si dicha
     * cuenta existe (comparar por Tipo y Nro. de Cuenta) se modifica, de lo contrario
     * informar que no existe esa cuenta.
    */
    parse_str(file_get_contents("php://input"), $putData);//-->Necesario para el funcionamiento de PUT
    if($_SERVER['REQUEST_METHOD'] === 'PUT') {
        if(isset($putData['numeroCuenta']) && isset($putData['nombre']) && isset($putData['apellido']) &&
        isset($putData['tipoDocumento']) && isset($putData['numeroDocumento']) && isset($putData['email']) &&
        isset($putData['tipoCuenta']) && isset($putData['moneda'])){ 
            $numeroCuenta = intval($putData['numeroCuenta']);
            $nombre = $putData['nombre'];
            $apellido = $putData['apellido'];
            $tipoDocumento = $putData['tipoDocumento'];
            $numeroDocumento = $putData['numeroDocumento'];
            $email = $putData['email'];
            $tipoCuenta = $putData['tipoCuenta'];
            $moneda = $putData['moneda'];

            $jsonFilenameCuentas = './archivos/banco.json';
            $cuentas = Cuenta::leerJSON($jsonFilenameCuentas); 
            $cuentaExistente = Cuenta::buscarPorNumeroCuenta($cuentas,$numeroCuenta,$tipoCuenta); 

            if($cuentaExistente !== null){
                if(Cuenta::actualizarCuenta($cuentas,
                new Cuenta($numeroCuenta,$nombre,$apellido,$tipoDocumento,
                $numeroDocumento,$email,$tipoCuenta,$moneda,$cuentaExistente->getSaldo()),
                $jsonFilenameCuentas)){
                    echo json_encode(['SUCCESS' => 'La cuenta fue actualizada correctamente!<br>']);
                }
                else
                    echo json_encode(['ERROR' => 'No se ha podido actualizar la cuenta!<br>']);
                
            }
            else
                echo json_encode(['respuesta'=>'No hay ninguna cuenta registrada con numero ' . $numeroCuenta .' y tipo de cuenta: ' . $tipoCuenta .'<br>']);

        }
        else
            echo json_encode(['ERROR' => 'Se necesitan todos los parametros para seguir!<br>']);

    }
