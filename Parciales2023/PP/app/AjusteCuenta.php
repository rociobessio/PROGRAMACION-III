<?php
    require_once "./classes/Retiro.php";
    require_once "./classes/Deposito.php";
    require_once "./classes/Ajuste.php";
    /** 
     * 7- AjusteCuenta.php (por POST),
     * Se ingresa el número de extracción o depósito afectado al ajuste y el motivo del
     * mismo. El número de extracción o depósito debe existir.
     * Guardar en el archivo ajustes.json
     * Actualiza en el saldo en el archivo banco.json
    */
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        if(isset($_POST['motivoAjuste']) && isset($_POST['ajusteMonto'])){
            $ajuste = floatval($_POST['ajusteMonto']);
            $motivo = $_POST['motivoAjuste'];

            if(isset($_POST['nroExtraccion'])){//-->Es una extraccion
                $nroExtraccion = intval($_POST['nroExtraccion']);  
                if(Ajuste::generarAjuste($motivo,$ajuste,"extracciones",$nroExtraccion)){
                    echo json_encode(['SUCCESS' => 'Se ha generado el ajuste correctamente!']);
                }
                else{
                    echo json_encode(['ERROR' => 'No se ha podido generar el ajuste!']);
                } 
            }
            elseif(isset($_POST['nroDeposito'])){//-->Es un deposito
                $nroDeposito = intval($_POST['nroDeposito']); 
                if(Ajuste::generarAjuste($motivo,$ajuste,"depositos",$nroDeposito)){
                    echo json_encode(['SUCCESS' => 'Se ha generado el ajuste correctamente!']);
                }
                else{
                    echo json_encode(['ERROR' => 'No se ha podido generar el ajuste!']);
                } 
            }
        }
        else
            echo json_encode(['ERROR' => 'Faltan parametros OBLIGARTORIOS por ingresar!']);
    }