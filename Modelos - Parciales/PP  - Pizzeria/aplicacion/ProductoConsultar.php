<?php
    require_once "./clases/Producto.php";
    require_once "./clases/Venta.php";

    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        //-->Puede variar segun el producto.
        if(isset($_POST['sabor']) && isset($_POST['tipo'])){
            $sabor = $_POST['sabor'] ;
            $tipo = $_POST['tipo'];

            $jsonFile = './archivos/Pizza.json';
            $productos = Venta::ObtenerArray($jsonFile);

            $existe = Producto::BuscarProducto($productos,$sabor,$tipo);

            if($existe){
                echo "[Si hay!]";
           }
           else
                echo "[No se encuentra el producto solicitado!]";
        }
        else
            echo "[Se necesitan todos los datos para seguir!]";
    }