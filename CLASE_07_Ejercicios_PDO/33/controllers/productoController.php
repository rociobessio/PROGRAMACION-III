<?php

    require_once "./33/classes/Producto.php";

    class ProductoController{


        public function altaProducto($nombre,$tipo,$stock,$precio){
            $producto = new Producto($nombre, $precio, $tipo, $stock);
            // var_dump($producto);
            if($producto->registrarOActualizarProducto()){
                return true;
            }
            else{
                // var_dump($producto);
                return false;
            }
        }

        public function modificarProducto($codBarra,$nombre,$tipo,$stock,$precio){
            return Producto::modificarProducto($nombre,$tipo,$stock,$precio,$codBarra);
        }
    }

    