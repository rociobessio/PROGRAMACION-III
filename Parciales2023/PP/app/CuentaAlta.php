<?php

    require_once "./classes/Cuenta.php";
    require_once "./Uploader.php";

    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        if(isset($_POST['nombre']) && isset($_POST['apellido']) && isset($_POST['tipoDocumento']) &&
        isset($_POST['numeroDocumento']) && isset($_POST['email']) && isset($_POST['tipoCuenta']) &&
        isset($_POST['moneda']) && isset($_POST['saldo']) && isset($_FILES['imagen'])){ 
            $imagen = $_FILES['imagen'];
            $saldo = floatval($_POST['saldo']);

            $jsonFilename = './archivos/banco.json';
            $archivoGuardar = new Uploader('./ImagenesDeCuentas/2023/');
            
            if(validarIngreso()){
                $cuentas = Cuenta::leerJSON($jsonFilename); 
                $cuenta = new Cuenta(empty($cuentas) ? 100000 : (Cuenta::obtenerUltimoID($cuentas) + 1),
                $_POST['nombre'],$_POST['apellido'],$_POST['tipoDocumento'],$_POST['numeroDocumento'],$_POST['email'],
                $_POST['tipoCuenta'],$_POST['moneda'],$saldo);
    
                if(Cuenta::cargarCuenta($cuenta,$cuentas,$saldo,$jsonFilename)){

                    if($archivoGuardar){//-->Intento guardar la imagen
                        $nombreImagen = $cuenta->getID().'_' . $cuenta->getTipoCuenta() . '.jpg';
                        $archivoGuardar->guardarImagen($_FILES['imagen']['tmp_name'],$nombreImagen);
                    }
                    else{
                        echo json_encode(['WARNING' => 'No se ha podido guardar la imagen de la cuenta!<br>']);
                    }
                    echo json_encode(['SUCCESS' => 'La cuenta fue guardada correctamente!<br>']);
                }
                else
                    echo json_encode(['ERROR' => 'Algo salio mal! La cuenta no pudo guardarse.<br>']);
            }

        }
        else
            echo json_encode(['ERROR' => 'Faltan parametros por ingresar!']);
    }

    function validarIngreso(){
        if(!Cuenta::validarMoneda($_POST['moneda'])){
            echo json_encode(['error' => 'La moneda no es valida, solo se aceptan $ o USS']);
            return false;
        }
        if(!Cuenta::validarTipoCuenta($_POST['tipoCuenta'])){
            echo json_encode(['error' =>  'El tipo de cuenta no es valida, solo se acepta CC o CA']);
            return false;
        }
        if(!Cuenta::validarTipoDocumento($_POST['tipoDocumento'])){
            echo json_encode(['error' =>  'El tipo de documento no es valido, solo se acepta DNI,LC,LE o CI.']);
            return false;
        }
        return true;
    }