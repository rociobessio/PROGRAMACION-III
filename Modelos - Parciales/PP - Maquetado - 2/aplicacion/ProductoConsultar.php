<?php

    require_once "./clases/Producto.php";

    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        //-->Puede variar segun el producto.
        if(isset($_POST['sabor']) && isset($_POST['tipo'])){
            $sabor = $_POST['sabor'] ;
            $tipo = $_POST['tipo'];
        
            $jsonFile = './archivos/productos.json';//-->Modificar
            $productos = Producto::leerJSON($jsonFile);

            $mensaje = Producto::buscarPor($productos,$sabor,$tipo);
            echo json_encode(['resultado' => $mensaje]);
        }
        else
            echo json_encode(['error' => 'Se necesitan todos los datos para seguir!']);
    }