<?php

    class Producto{

        public static function ValidarTipo($tipo){
            $tipos = ["SIMPLE","DOBLE"];
                if(in_array(strtoupper($tipo),$tipos))
                    return true;
                else
                    return false;
        }

        public static function ValidarAderezo($aderezo){
            $aderezos = ["MOSTAZA","MAYONESA", "KETCHUP"];
                if(in_array(strtoupper($aderezo),$aderezos))
                    return true;
                else
                    return false;
        }

        /**
         * Funcion que permite verificar si hay
         * stock de un producto.
         * 
         * @param obj el producto.
         * @param int cantidad del producto.
         * 
         * @return bool true si hay stock, false sino.
         */
        public static function VerificarStock($producto,$cantidad){
            return $producto['cantidad'] >= $cantidad;
        }


        /**
         * Funcion que me permitirá buscar una coincidencia
         * en el array para saber si existe el producto.
         * 
         * @param array el array de productos.
         * @param string nombre del producto.
         * @param string tipo del producto.
         * 
         * @return object el objeto si lo encuentra, null sino.
         */
        public static function BuscarProducto($productos,$nombre,$tipo){
            if($productos !== null){
                foreach ($productos as $producto) {
                     var_dump($producto);
                    if($producto['nombre'] === $nombre && $producto['tipo'] === $tipo){
                        return $producto;
                    }
               }
            }
          return null;
       }

       /**
        * Me permitirá actualizar un producto en el 
        * archivo json.
        * @param array el array de productos.
        * @param object el producto existente.
        * @param string el path del archivo.
        * 
        * @return 
        */
       public static function ActualizarProducto(&$productos, $productoExistente, $json_file)
        {
            foreach ($productos as &$hamburguesa) {
                if ($hamburguesa["id"] == $productoExistente["id"]) {
                    $hamburguesa = $productoExistente;
                    break;
                }
            }

            $productosJSON = json_encode($productos, JSON_PRETTY_PRINT);
            file_put_contents($json_file, $productosJSON);
        }
    }