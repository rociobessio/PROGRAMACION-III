<?php
    require_once "./clases/Producto.php";
    require_once "./clases/Venta.php";

    parse_str(file_get_contents("php://input"), $putData);//-->Necesario para el funcionamiento de PUT

    if($_SERVER['REQUEST_METHOD'] === 'PUT') {
        if (isset($putData['numeroPedido']) && isset($putData['tipo']) && isset($putData['sabor']) &&
            isset($putData['cantidad']) && isset($putData['emailUsuario'])
        ){
            $numeroPedido = intval($putData['numeroPedido']);
            $tipo = $putData['tipo'];
            $sabor = $putData['sabor'];
            $cantidad = intval($putData['cantidad']);
            $emailUsuario = $putData['emailUsuario'];

            if(Venta::modificarVenta($numeroPedido,$sabor,$tipo,$cantidad,$emailUsuario)){
                echo json_encode(['SUCCESS' => 'La venta fue modificada correctamente!<br>']);
            }
            else
                echo json_encode(['ERROR' => 'La venta fue no pudo modificarse!<br>']);
        }
        else
            echo json_encode(['ERROR' => 'Se necesitan todos los parametros para seguir!<br>']);
    }