<?php

    require_once "Venta.php";

    if($_SERVER['REQUEST_METHOD'] === 'DELETE') {

        if(isset($_GET['numeroPedido'])){
            $numeroPedido = intval($_GET['numeroPedido']);

            if(Venta::EliminarVenta($numeroPedido)){
                echo json_encode(['OK' => 'Venta eliminada correctamente!<br>']);
            }
            else
                echo json_encode(['error' => 'No se pudo eliminar la venta solicitada!<br>']);

        } else {
            echo json_encode(['error' => 'Faltan el parametro numero de pedido por ingregsar<br>']);
        }
    }
