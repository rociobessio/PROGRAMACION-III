<?php
    include_once "./clases/Venta.php";
    include_once "./clases/Producto.php";
    include_once "./Uploader.php";

    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        if(isset($_POST['emailUsuario']) && isset($_POST['tipo']) && isset($_POST['sabor']) &&
            isset($_POST['cantidad']) && isset($_FILES['imagen'])){
            $emailUsuario = $_POST['emailUsuario']; 
            $tipo = $_POST['tipo'];
            $sabor = $_POST['sabor'];//-->Puede variar
            $cantidad = intval($_POST['cantidad']); 
            $imagen = $_FILES['imagen'];

            $jsonFileProductos = './archivos/productos.json"';
            $archivoGuardar = new Uploader('./ImagenesDeLaVenta/');
            $productos = Producto::leerJSON($jsonFileProductos);//-->Traigo el array
            $productoExistente = Producto::buscarProducto($productos,$sabor,$tipo);//-->Busco si existe

            if(Venta::generarVenta($productoExistente,$productos,$cantidad,$emailUsuario)){

                if($archivoGuardar){
                    $pathImagenVenta = Uploader::crearPathImagenVenta($emailUsuario,$sabor,$tipo);
                    $archivoGuardar->guardarImagen($_FILES['imagen']['tmp_name'], $pathImagenVenta);    
                }
                else{
                    echo json_encode(['WARNING' => 'No se ha podido guardar la imagen de la venta!<br>']);
                }
                echo json_encode(['SUCCESS' => 'La venta fue generada correctamente!<br>']);
            }
            else
                echo json_encode(['ERROR' => 'No se pudo generar la venta!']);    
        }
        else
            echo json_encode(['ERROR' => 'Se necesitan todos los datos para seguir!']);
    }