<?php

    require_once "./clases/Producto.php";
    require_once "./clases/Venta.php";

    if($_SERVER['REQUEST_METHOD'] === 'DELETE') {

        if(isset($_GET['numeroPedido'])){
            $numeroPedido = intval($_GET['numeroPedido']);
            // var_dump($numeroPedido);

            if(Venta::eliminarVenta($numeroPedido)){
                echo json_encode(['OK' => 'Venta eliminada correctamente!<br>']);
            }
            else
                echo json_encode(['error' => 'No se pudo eliminar la venta solicitada!<br>']);
        }
        else
            echo json_encode(['error' => 'Se necesita el numero de pedido para seguir!<br>']);
    }
