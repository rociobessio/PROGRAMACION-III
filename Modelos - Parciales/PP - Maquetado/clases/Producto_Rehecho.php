<?php

    require_once "Archivo.php";
    /**
     * Rehaciendo la clase Producto
     * utilizando la interfaz JsonSerializable
     */
    class Producto_Rehecho implements JsonSerializable{
//***************************************************** ATRIBUTOS *****************************************************
        private $_id;
        private $_precio;
        private $_tipo;
        private $_aderezo;
        private $_cantidad; 
        private $_imagen;

//***************************************************** PROPIEDADES GETTERS *****************************************************
        public function getID(){
            return $this->_id;
        }
        public function getPrecio(){
            return $this->_precio;
        }
        public function getTipo(){
            return $this->_tipo;
        }
        public function getAderezo(){
            return $this->_aderezo;
        }
        public function getCantidad(){
            return $this->_cantidad;
        }
        public function getImagen(){
            return $this->_imagen;
        }
//***************************************************** PROPIEDADES SETTERS *****************************************************
        public function setPrecio($value){
            $this->_precio = $value;
        }
        public function setTipo($value){
            $this->_tipo = $value;
        }
        public function setAderezo($value){
            $this->_aderezo = $value;
        }
        public function setCantidad($value){
            $this->_cantidad = $value;
        }
        public function setImagen($value){
            $this->_imagen = $value;
        }
//***************************************************** CONSTRUCTOR *****************************************************
        public function __construct($precio,$tipo,$aderezo,$cantidad,$imagen = null) {
            $productos = Archivo::ObtenerArray('./archivos/productos.json');//-->traigo el array
            $this->_id = empty($productos) ? 1 : (count($productos) + 1);//-->Asigo id autoincremental.
            $this->_precio = floatval($precio);
            $this->_tipo = $tipo;
            $this->_aderezo = $aderezo;
            $this->_cantidad = intval($cantidad);
            $this->_imagen = ($imagen !== null) ? $imagen : '';
        }
//***************************************************** FUNCIONES *****************************************************

        // Implementación del método jsonSerialize
        public function jsonSerialize() {
            return [
                'id' => $this->_id, 
                'tipo' => $this->_tipo,
                'aderezo' => $this->_aderezo,
                'precio' => $this->_precio,
                'cantidad' => $this->_cantidad,
                'imagen' => $this->_imagen,
            ];
        }

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
                     //var_dump($hamburguesa);
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