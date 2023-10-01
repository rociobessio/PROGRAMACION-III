<?php
    require_once "../clases/Producto.php";
    require_once "../clases/Venta.php";

    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        //-->Puede variar segun el producto.
        if(isset($_POST['nombre']) && isset($_POST['tipo'])){
            $nombre = $_POST['nombre'] ;
            $tipo = $_POST['tipo'];

            $jsonFile = '../archivos/hamburguesas.json';
            $productos = Venta::ObtenerArray($jsonFile);

            $existe = Producto::BuscarProducto($productos,$nombre,$tipo);

            if($existe){
                echo "[Si hay!]";
           }
           else
                echo "[No se encuentra el producto solicitado!]";
        }
        else
            echo "[Se necesitan todos los datos para seguir!]";
    }