<?php
    require_once "./31/controllers/ventaController.php";
    /**
     * Recibe los datos del producto(código de barra), del usuario (el id )y la cantidad de ítems
     * ,por POST .
     * Verificar que el usuario y el producto exista y tenga stock.
     * Retorna un :
     * “venta realizada”Se hizo una venta
     * “no se pudo hacer“si no se pudo hacer
     * Hacer los métodos necesarios en las clases
    */

    if(isset($_POST['codBarra']) && isset($_POST['idUsuario']) && isset($_POST['cantidad'])){ 
        $ventaController = new VentaController();
        $resultado = $ventaController->realizarVenta(intval($_POST['codBarra']),intval($_POST['idUsuario']),intval($_POST['cantidad']));
        echo $resultado ? json_encode(['SUCCESS' => 'Producto registrado correctamente!<br>']) :
                        json_encode(['ERROR' => 'No se pudo Producto el usuario<br>']);
    }
    else
        echo json_encode(['error' => 'Faltan parametros por ingresar!<br>']);