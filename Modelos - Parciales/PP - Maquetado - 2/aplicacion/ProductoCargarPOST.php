<?php
        require_once "./clases/Producto.php";

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            if(isset($_POST['precio']) && isset($_POST['tipo']) && isset($_POST['sabor']) &&
            isset($_POST['cantidad']) && isset($_FILES['imagen'])){
                //-->Obtengo los valores 
                $precio = floatval($_POST['precio']);
                $tipo = $_POST['tipo'];
                $sabor = $_POST['sabor'];
                $cantidad = intval($_POST['cantidad']); 
                $imagen = $_FILES['imagen'];
    
                if(!Producto::validarTipo($tipo)){//-->Dependiendo del producto varia.
                    echo "[Se debe de ingresar un tipo valido!]";
                    exit;
                }
    
                $jsonFileProductos = './archivos/productos.json';
                $archivoGuardar = new Uploader('./ImagenesDeProductos/2023/');
                $productos = Producto::leerJSON($jsonFileProductos); 
                $productoEncontrado = Producto::buscarProducto($productos,$sabor,$tipo);
    
                if(Producto::cargarProducto($productoEncontrado,$productos,$precio,$cantidad,$jsonFileProductos,$tipo,$sabor,$archivoGuardar)){
                    //-->completar el alta con imagen del producto
                    if($archivoGuardar){
                        $imageFileName = $tipo . '_' . $sabor . '_' . uniqid() . '.jpg';
                        $archivoGuardar->guardarImagen($_FILES['imagen']['tmp_name'], $imageFileName);
                    }
                    else{
                        echo json_encode(['WARNING' => 'No se ha podido guardar la imagen del producto!<br>']);
                    }

                    echo json_encode(['SUCCESS' => 'El producto fue guardado correctamente!<br>']);
                }
                else
                    echo json_encode(['ERROR' => 'Algo salio mal! El producto fue no pudo guardarse.<br>']);
            } 
            else {
                echo json_encode(['ERROR' => 'Faltan parametros por ingresar!<br>']);
            }
        }

