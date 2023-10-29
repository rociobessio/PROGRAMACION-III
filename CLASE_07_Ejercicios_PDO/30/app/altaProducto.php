<?php
    require_once "./30/controllers/productoController.php";
    /**
     * Aplicación Nº 30 ( AltaProducto BD)
     * Archivo: altaProducto.php
     * método:POST
     * Recibe los datos del producto(código de barra (6 sifras ),nombre ,tipo, stock, precio )por POST
     * , carga la fecha de creación y crear un objeto ,se debe utilizar sus métodos para poder
     * verificar si es un producto existente,
     * si ya existe el producto se le suma el stock , de lo contrario se agrega .
     * Retorna un :
     * “Ingresado” si es un producto nuevo
     * “Actualizado” si ya existía y se actualiza el stock.
     * “no se pudo hacer“si no se pudo hacer
     * Hacer los métodos necesarios en la clase
     * 
     * Bessio Rocio Soledad
     */
    if(isset($_POST['nombre']) && isset($_POST['tipo']) && isset($_POST['precio']) &&
    isset($_POST['cantidad'])){
    $productoController = new ProductoController();
    $resultado = $productoController->altaProducto($_POST['nombre'],$_POST['tipo'],intval($_POST['cantidad']),floatval($_POST['precio']));
    echo $resultado ? json_encode(['SUCCESS' => 'Producto registrado correctamente!<br>']) :
                    json_encode(['ERROR' => 'No se pudo Producto el usuario<br>']);
    }
    else
    echo json_encode(['error' => 'Faltan parametros por ingresar!<br>']);