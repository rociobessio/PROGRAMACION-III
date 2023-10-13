<?php
    require_once "./Uploader.php";

    class Producto{
                
//********************************************* ATRIBUTOS *********************************************
        public $_id;
        public $_sabor;
        public $_precio;
        public $_tipo;
        public $_cantidad;
        // public $_imagen;
//********************************************* PROPIEDADES SETTERS *********************************************
        public function setID($id){
            if (isset($id) && is_numeric($id)){
                $this->_id = $id;
            }
        }
        public function setSabor($sabor){
            if(isset($sabor) && !empty($sabor)) {
                $this->_sabor = $sabor;
            }
        }
        public function setPrecio($precio){
            if(isset($precio) && is_float($precio)){
                $this->_precio = $precio;
            }
        }
        public function setTipo($tipo){
            if (isset($tipo)){
                $this->_tipo = $tipo;
            }
        }
        public function setCantidad($cantidad){
            if (!empty($cantidad) && is_numeric($cantidad)){
                $this->_cantidad = $cantidad;
            }
        }
        // public function setImagen($imagen){
        //     $this->_imagen = $imagen;
        // }
 //********************************************* PROPIEDADES GETTERS *********************************************
        public function getID(){
            return $this->_id;
        }
        public function getSabor(){
            return $this->_sabor;
        }
        public function getPrecio(){
            return $this->_precio;
        }
        public function getTipo(){
            return $this->_tipo;
        }
        public function getCantidad(){
            return $this->_cantidad;
        }
        // public function getImagen(){
        //     return $this->_imagen;
        // }
//********************************************* CONSTRUCTOR *********************************************
        public function __construct($id, $sabor, $precio, $tipo, $cantidad){
            $this->setID($id);
            $this->setSabor($sabor);
            $this->setPrecio($precio);
            $this->setTipo($tipo);
            $this->setCantidad($cantidad);
            //$this->setImagen(($imagen !== null) ? $imagen : '');//-->Si no trae imagen que cargue con ''
        }
//********************************************* FUNCIONES *********************************************
        public static function validarTipo($tipo){
            $tipos = ["MOLDE","PIEDRA"];
                if(in_array(strtoupper($tipo),$tipos))
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
        public function verificarStock($cantidad){
            return $this->getCantidad() >= $cantidad;
        }

        /**
         * Me permitirá actualizar un producto en el  archivo json.
         * @param array el array de productos.
         * @param object el producto existente.
         * @param string el path del archivo.
         * 
         * @return bool retorna si se pudo guardar la actualizacion 
         * del producto.
         */
        public static function actualizarProducto(&$productos, $productoExistente, $jsonFile){
            foreach ($productos as &$producto) {
                if ($producto->getID() == $productoExistente->getID()) {
                    $producto = $productoExistente;
                    // var_dump($producto);
                    break;
                }
            }
            return self::guardarJSON($productos,$jsonFile);
        }

        /**
         * Funcion que me permitirá buscar una coincidencia
         * en el array para saber si existe el producto.
         * 
         * @param array el array de productos. 
         * 
         * @return object el objeto si lo encuentra, null sino.
         */
        public static function buscarProducto($productos,$sabor,$tipo){
            if($productos !== null){
                foreach ($productos as $producto) { 
                    if($producto->getSabor() === $sabor && $producto->getTipo() === $tipo){
                        return $producto;
                    }
               }
            }
          return null;
       }

        /**
        * Me permitira filstrar si existe o no 
        * el tipo y sabor dado por parametro.
        * @param array el array de productos
        * @param string el sabor a buscar.
        * @param string el tipo a buscar.
        * @return string el mensaje final.
        */
        public static function buscarPor($productos,$sabor,$tipo){
            $mensaje = "";

            $productosConSabor = [];
            $productosConTipo = [];
        
            //-->Busco el producto mediante el sabor y el tipo que recibo
            foreach ($productos as $producto) {
                if ($producto->getSabor() === $sabor && $producto->getTipo() === $tipo) {
                    return 'Si Hay de Sabor: ' . $sabor . ' y Tipo: ' . $tipo;
                }
                if ($producto->getSabor() === $sabor) {
                    $productosConSabor[] = $producto;
                }
                if ($producto->getTipo() === $tipo) {
                    $productosConTipo[] = $producto;
                }
            }
            //-->Si los arrays no estan vacios:
            if (!empty($productosConSabor) && !empty($productosConTipo)) {
                $mensaje = 'Si Hay de Sabor y Tipo';
            } elseif (!empty($productosConSabor)) {
                $mensaje = 'Solo hay de Sabor: ' . $sabor;
            } elseif (!empty($productosConTipo)) {
                $mensaje = 'Solo hay de Tipo: ' . $tipo;
            } else {
                $mensaje = 'No hay Producto de tipo: ' . $tipo . ' ni de sabor ' . $sabor;
            }
        
            return $mensaje;
        }
       
        /**
        * Esta funcion me permitira corroborar si el producto existe o no.
        * Si no existe quiere decir que es un nuevo producto dandolo de alta en
        * el archivo, si existe actualiza su cantidad y precio en el archivo. 
        */
        public static function cargarProducto($productoExistente,$productos,$precio,$cantidad,$jsonFile,$tipo,$sabor){
            
            if($productoExistente !== null){//-->Quiere decir que existe
                $productoExistente->setPrecio($precio);
                $productoExistente->setCantidad($productoExistente->getCantidad() + $cantidad);//-->Se actualiza el stock
        
                //-->Debo de actualizarlo en el array
                return self::actualizarProducto($productos,$productoExistente,$jsonFile);
            }
            else{//-->No existe nuevo producto. 
                $nuevaProducto = new Producto(
                    empty($productos) ? 1 : (count($productos) + 1),//-->Simulacion de ID autoincremental
                    $sabor,
                    $precio,
                    $tipo,
                    $cantidad); 
        
                $productos[] = $nuevaProducto;//-->Agrego al array el nuevo producto
                
                return self::guardarJSON($productos,$jsonFile);
            }
            return false;
        }

        /**
         * Esta funcion lee un archivo json de pizzas.
         * 
         * @param string la ubicacion del archivo .json
         * @return array retornara un array vacio sino pudo cargar,
         * o retorna el array cargado.
         */
        public static function leerJSON($jsonFile){
            $productos = array();
            if(file_exists($jsonFile)){
                $archivo = fopen($jsonFile, "r");
                if ($archivo) {
                    $fileSize = filesize($jsonFile);
                    if ($fileSize > 0) {
                        $json = fread($archivo, $fileSize);
                        $productosJson = json_decode($json, true);
                        foreach ($productosJson as $producto) {
                            array_push($productos, new Producto(
                                intval($producto["_id"]), 
                                $producto["_sabor"], 
                                floatval($producto["_precio"]), 
                                $producto["_tipo"], 
                                intval($producto["_cantidad"]), 
                            ));
                        }
                    }
                    fclose($archivo);
                }
            }
            return $productos;
        }

        /**
         * Me permitira guardar el array de productos en el archivo
         * json.
         * @param array el array de productos.
         * @param string ruta del archivo
         * @return bool true si pudo false sino.
         */
        public static function guardarJSON($productos, $jsonFilename){
            $success = false;
            try {
                $file = fopen($jsonFilename, "w");
                if ($file) {
                    $json = json_encode($productos, JSON_PRETTY_PRINT);
                    fwrite($file, $json);
                    $success = true;
                }
            } catch (\Throwable $th) {
                echo "Error al guardar el archivo";
            } finally {
                fclose($file);
                return $success;
            }
        }
    }