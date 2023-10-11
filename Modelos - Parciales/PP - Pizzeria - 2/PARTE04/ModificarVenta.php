<?php
    require_once "Pizza.php";
    require_once "Venta.php";

    parse_str(file_get_contents("php://input"), $putData);//-->Necesario para el funcionamiento de PUT

    // var_dump($putData);

    if($_SERVER['REQUEST_METHOD'] === 'PUT') {
        if (
            isset($putData['numeroPedido']) &&
            isset($putData['tipo']) &&
            isset($putData['sabor']) &&
            isset($putData['cantidad']) &&
            isset($putData['emailUsuario'])
        ) {
            $numeroPedido = intval($putData['numeroPedido']);
            $tipo = $putData['tipo'];
            $sabor = $putData['sabor'];
            $cantidad = intval($putData['cantidad']);
            $emailUsuario = $putData['emailUsuario'];
    
            if (Venta::ModificarVenta($numeroPedido, $sabor, $tipo, $cantidad, $emailUsuario)) {
                echo '[Venta modificada!]<br>';
            } else {
                echo '[Ocurrió un error al querer modificar la venta!]<br>';
            }
        } else {
            echo json_encode(['error' => 'Faltan parametros por ingregsar<br>']);
        }
    }
