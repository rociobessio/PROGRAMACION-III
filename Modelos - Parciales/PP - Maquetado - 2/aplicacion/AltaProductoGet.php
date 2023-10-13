<?php
    require_once "./clases/Producto.php";

    if($_SERVER['REQUEST_METHOD'] === 'GET'){
        if(isset($_GET['precio']) && isset($_GET['tipo']) && isset($_GET['sabor']) &&
        isset($_GET['cantidad']) && isset($_GET['imagen'])){
            //-->Obtengo los valores 
            $precio = floatval($_GET['precio']);
            $tipo = $_GET['tipo'];
            $sabor = $_GET['sabor'];
            $cantidad = intval($_GET['cantidad']); 
            $imagen = $_GET['imagen'];

            if(!Producto::ValidarTipo($tipo)){//-->Dependiendo del producto varia.
                echo "[Se debe de ingresar un tipo valido!]";
                exit;
            }

            $jsonFileProductos = './archivos/productos.json';
            $directorioImagenProducto = './ImagenesDeProductos/2023/';
            $productos = Producto::LeerJSON($jsonFileProductos);
            $productoEncontrado = Producto::BuscarProducto($productos,$sabor,$tipo);

            if(Producto::CargarProducto($productoEncontrado,$productos,$precio,$cantidad,$jsonFileProductos,$tipo,$sabor,$directorioImagenProducto)){
                echo json_encode(['SUCCESS' => 'El producto fue guardado correctamente!<br>']);
            }
            else
                echo json_encode(['ERROR' => 'Algo salio mal! El producto fue no pudo guardarse.<br>']);
        } 
        else {
            echo json_encode(['error' => 'Faltan parametros por ingresar!<br>']);
        }
    }