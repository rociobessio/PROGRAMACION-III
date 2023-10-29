<?php

    /**
     * Aplicación Nº 33 ( ModificacionProducto BD)
     * Archivo: modificacionproducto.php
     * método:POST
     * Recibe los datos del producto(código de barra (6 sifras ),nombre ,tipo, stock, precio )por POST
     * ,
     * crear un objeto y utilizar sus métodos para poder verificar si es un producto existente,
     * si ya existe el producto el stock se sobrescribe y se cambian todos los datos excepto:
     * el código de barras .
     * Retorna un :
     * “Actualizado” si ya existía y se actualiza
     * “no se pudo hacer“si no se pudo hacer
     * Hacer los métodos necesarios en la clase
     */
    require_once "./33/controllers/productoController.php";

    if(isset($_POST['nombre']) && isset($_POST['tipo']) && isset($_POST['precio']) &&
    isset($_POST['cantidad']) && isset($_POST['codBarra'])){
    $productoController = new ProductoController();
    $resultado = $productoController->modificarProducto(intval($_POST['codBarra']),$_POST['nombre'],$_POST['tipo'],intval($_POST['cantidad']),
                                                        floatval($_POST['precio']));
    echo $resultado ? json_encode(['SUCCESS' => 'Producto modificado correctamente!<br>']) :
                    json_encode(['ERROR' => 'No se pudo modificar el producto<br>']);
    }
    else
        echo json_encode(['error' => 'Faltan parametros por ingresar!<br>']);