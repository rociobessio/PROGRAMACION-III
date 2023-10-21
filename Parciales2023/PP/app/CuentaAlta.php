<?php

    require_once "./classes/Cuenta.php";
    require_once "./classes/Uploader.php";

    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        if(isset($_POST['nombre']) && isset($_POST['apellido']) && isset($_POST['tipoDocumento']) &&
        isset($_POST['numeroDocumento']) && isset($_POST['email']) && isset($_POST['tipoCuenta']) &&
        isset($_POST['moneda']) && isset($_POST['saldo']) && isset($_FILES['imagen'])){ 
            $imagen = $_FILES['imagen'];
            $saldo = floatval($_POST['saldo']);

            $jsonFilename = './archivos/banco.json'; 
            $cuenta = new Cuenta(mt_rand(100000,999999),
            $_POST['nombre'],$_POST['apellido'],$_POST['tipoDocumento'],$_POST['numeroDocumento'],$_POST['email'],
            $_POST['tipoCuenta'],$_POST['moneda'],$saldo);
    
            if(Cuenta::cargarCuenta($cuenta,$saldo,$jsonFilename,$imagen)){
                echo json_encode(['SUCCESS' => 'La cuenta fue guardada correctamente!<br>']);
            }
            else
                echo json_encode(['ERROR' => 'Algo salio mal! La cuenta no pudo guardarse.<br>']); 
        }
        else
            echo json_encode(['ERROR' => 'Faltan parametros por ingresar!']);
    } 