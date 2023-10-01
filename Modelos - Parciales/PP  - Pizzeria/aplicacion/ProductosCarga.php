<?php

    require_once "./clases/Producto.php";
    require_once "./clases/Venta.php";

    if($_SERVER['REQUEST_METHOD'] === 'POST'){

        if(isset($_POST['precio']) && isset($_POST['tipo']) && isset($_POST['sabor']) &&
           isset($_POST['cantidad']) && isset($_FILES['imagenProducto'])){
            //-->Obtengo los valores 
            $precio = floatval($_POST['precio']);
            $tipo = $_POST['tipo'];
            $sabor = $_POST['sabor'];
            $cantidad = intval($_POST['cantidad']); 
            $imagen = $_FILES['imagenProducto'];

            if(!Producto::ValidarTipo($tipo)){
                echo "[Se debe de ingresar un tipo valido!]";
                exit;
            }
 
            $jsonFile = './archivos/Pizza.json';
            $directorioImagen = './ImagenesDePizzas/2023/';
            
            $productos = Venta::ObtenerArray($jsonFile);//-->Obtengo las hamburguesas.
            $productoExistente = Producto::BuscarProducto($productos,$sabor,$tipo);//-->Busco el producto.

            if(AltaProducto($productoExistente,$productos,$precio,$cantidad,$jsonFile,$tipo,$sabor,$imagen,$directorioImagen)){
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
    function AltaProducto($productoExistente,$productos,$precio,$cantidad,$jsonFile,$tipo,$sabor,$imagen=null,$directorioImagen=null){
        
        // var_dump($productoExistente);
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
                'tipo' => $tipo,
                'sabor' => $sabor,
                'precio' => $precio,
                'cantidad' => $cantidad,
                'imagen' => '',
            ]; 

            //-->Creo la ruta para guardar la imagen del producto
            $nombreimg = $sabor . '_' . $tipo . '_' . uniqid() . '.jpg' ;  
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