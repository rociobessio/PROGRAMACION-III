<?php
    require_once "./30/controllers/productoController.php";

    if(isset($_POST['nombre']) && isset($_POST['tipo']) && isset($_POST['precio']) &&
    isset($_POST['cantidad'])){
    $productoController = new ProductoController();
    $resultado = $productoController->altaProducto($_POST['nombre'],$_POST['tipo'],intval($_POST['cantidad']),floatval($_POST['precio']));
    echo $resultado ? json_encode(['SUCCESS' => 'Producto registrado correctamente!<br>']) :
                    json_encode(['ERROR' => 'No se pudo Producto el usuario<br>']);
    }
    else
    echo json_encode(['error' => 'Faltan parametros por ingresar!<br>']);