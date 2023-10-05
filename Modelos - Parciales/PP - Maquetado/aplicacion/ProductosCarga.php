<?php

    require_once "./clases/Producto.php";
    require_once "./clases/Archivo.php";

    if($_SERVER['REQUEST_METHOD'] === 'POST'){

        if(isset($_POST['nombre']) && isset($_POST['precio']) && isset($_POST['tipo']) && isset($_POST['aderezo']) &&
           isset($_POST['cantidad']) && isset($_FILES['imagen'])){
            //-->Obtengo los valores
            $nombre = $_POST['nombre'];
            $precio = floatval($_POST['precio']);
            $tipo = $_POST['tipo'];
            $aderezo = $_POST['aderezo'];
            $cantidad = intval($_POST['cantidad']);
            $imagen = $_FILES['imagen'];

            // if(!Producto::ValidarTipo($tipo) || !Producto::ValidarAderezo($aderezo)){
            //     echo "[Se debe de ingresar un tipo o aderezo valido!]";
            //     exit;
            // }

            $directorioImagen = './ImagenesDeProductos/2023/';
            $jsonFile = './archivos/productos.json';
            
            $productos = Archivo::ObtenerArray($jsonFile);//-->Obtengo los productos.
            $productoExistente = Producto::BuscarProducto($productos,$nombre,$tipo);//-->Busco el producto.

            if(AltaProducto($productoExistente,$productos,$precio,$cantidad,$jsonFile,$nombre,$tipo,$aderezo,$imagen,$directorioImagen)){
                echo "[Producto guardado correctamente!]";
            }
            else
                echo "[Ocurrio un error al querer guardar el producto!]";    
        }
        else
            echo "[Se necesitan todos los datos para poder seguir!]";
    }

    /**
     * Esta función me permitirá generar un nuevo producto.
     * Se haran sus validaciones pertinentes, se fija si existe o no,
     * de existir se debe de actualizar el producto. Sino existe se
     * genera desde 0 para luego guardarse en el archivo json y la imagen
     * del producto.
     * 
     * @return bool true si se pudo realizar, false sino.
     */
    function AltaProducto($productoExistente,$productos,$precio,$cantidad,$jsonFile,$nombre,$tipo,$aderezo,$imagen,$directorioImagen){
        if($productoExistente !== null){//-->Quiere decir que existe
            $productoExistente["precio"] = $precio;
            $productoExistente["cantidad"] += $cantidad;//-->Se actualiza el stock

            //-->Debo de actualizarlo en el array
            Producto::ActualizarProducto($productos,$productoExistente,$jsonFile);
        }
        else{//-->No existe nuevo producto. 
            $nuevoProducto = [ 
                //-->Simulacion de ID autoincremental, sino hay elementos en el array, lo asina para no romper.
                'id' => empty($productos) ? 1 : (count($productos) + 1),
                'nombre' => $nombre,
                'tipo' => $tipo,
                'aderezo' => $aderezo,
                'precio' => $precio,
                'cantidad' => $cantidad,
                'imagen' => '',//-->Se guardara en un principio vacia para luego asignarla.
            ]; 

            //-->Creo la ruta para guardar la imagen del producto
            $nombreimg = $nombre . '_' . $tipo . '_' . uniqid() . '.jpg' ;  
            $rutaImg = $directorioImagen . $nombreimg;

            if(move_uploaded_file($imagen['tmp_name'],$rutaImg)){
                $nuevoProducto['imagen'] = $rutaImg;//-->Asigno la imagen 
            }

            $productos[] = $nuevoProducto;//-->Agrego al array la nueva hamburguesa
        }

        //-->Ahora guardo nuevamente el archivo:
        $productosJSON = json_encode($productos,JSON_PRETTY_PRINT);

        if(file_put_contents($jsonFile,$productosJSON)){
            return true;
        }
        else
            return false;
    }