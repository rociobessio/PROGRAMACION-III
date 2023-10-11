<?php

    class Pizza{
//********************************************* ATRIBUTOS *********************************************
        public $_id;
        public $_sabor;
        public $_precio;
        public $_tipo;
        public $_cantidad;
        public $_imagen;
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

        public function setImagen($imagen){
            $this->_imagen = $imagen;
        }
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
        public function getImagen(){
            return $this->_imagen;
        }
//********************************************* CONSTRUCTOR *********************************************

        public function __construct($id, $sabor, $precio, $tipo, $cantidad,$imagen = null){
            $this->setID($id);
            $this->setSabor($sabor);
            $this->setPrecio($precio);
            $this->setTipo($tipo);
            $this->setCantidad($cantidad);
            $this->setImagen(($imagen !== null) ? $imagen : '');//-->Si no trae imagen que cargue con ''
        }
        
//********************************************* FUNCIONES *********************************************
        public static function ValidarTipo($tipo){
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
        public static function BuscarPizza($productos,$sabor,$tipo){
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
        * Esta function me permitirá buscar si existe dentro
        * del array la pizza si hay del sabor y tipo indicados.
        * @param array el array de pizzas.
        * @param string el sabor del la pizza.
        * @param string el tipo de la pizza.
        * @return string una string con el resultado
        */
       public static function BuscarPor($pizzas,$sabor,$tipo){
            $mensaje = "";

            $pizzasConSabor = [];
            $pizzasConTipo = [];
        
            //-->Busco las pizzas mediante el sabor y el tipo que recibo
            foreach ($pizzas as $pizza) {
                if ($pizza->getSabor() === $sabor && $pizza->getTipo() === $tipo) {
                    return 'Si Hay de Sabor: ' . $sabor . ' y Tipo: ' . $tipo;
                }
                if ($pizza->getSabor() === $sabor) {
                    $pizzasConSabor[] = $pizza;
                }
                if ($pizza->getTipo() === $tipo) {
                    $pizzasConTipo[] = $pizza;
                }
            }
            //-->Si los arrays no estan vacios:
            if (!empty($pizzasConSabor) && !empty($pizzasConTipo)) {
                $mensaje = 'Si Hay de Sabor y Tipo';
            } elseif (!empty($pizzasConSabor)) {
                $mensaje = 'Solo hay de Sabor: ' . $sabor;
            } elseif (!empty($pizzasConTipo)) {
                $mensaje = 'Solo hay de Tipo: ' . $tipo;
            } else {
                $mensaje = 'No hay Pizzas ' . $tipo . ' ni de sabor ' . $sabor;
            }
        
            return $mensaje;
       }

       /**
        * Esta función me permitirá generar un nuevo producto.
        * Se haran sus validaciones pertinentes, se fija si existe o no,
        * de existir se debe de actualizar el producto. Sino existe se
        * genera desde 0 para luego guardarse en el archivo json y la imagen
        * del producto.
        * 
        * @return bool true si pudo actualizar/agregar false sino.
        */
       public static function CargarProducto($pizzaExistente,$pizzas,$precio,$cantidad,$jsonFile,$tipo,$sabor,$imagen=null,$directorioImagen=null){

        if($pizzaExistente !== null){//-->Quiere decir que existe
            $pizzaExistente->setPrecio($precio);
            $pizzaExistente->setCantidad($pizzaExistente->getCantidad() + $cantidad);//-->Se actualiza el stock
    
            //-->Debo de actualizarlo en el array
            Pizza::ActualizarProducto($pizzas,$pizzaExistente,$jsonFile);
        }
        else{//-->No existe nuevo producto. 
            $nuevaPizza = new Pizza(empty($pizzas) ? 1 : (count($pizzas) + 1),
                                    $sabor,
                                    $precio,
                                    $tipo,
                                    $cantidad,
                                    $imagen);
    
            // //-->Creo la ruta para guardar la imagen del producto
            // $nombreimg = $sabor . '_' . $tipo . '_' . uniqid() . '.jpg' ;  
            // $rutaImg = $directorioImagen . $nombreimg;
    
            // if(move_uploaded_file($imagen['tmp_name'],$rutaImg)){
            //     $nuevaPizza['imagen'] = $rutaImg;//-->Asigno la imagen 
            // }
    
            $pizzas[] = $nuevaPizza;//-->Agrego al array la nueva hamburguesa
        }
            if(Pizza::GuardarJSON($pizzas,$jsonFile)){
                return true;
            }
            else
                return false;
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
            foreach ($productos as &$producto) {
                if ($producto->getID() == $productoExistente->getID()) {
                    $producto = $productoExistente;
                    // var_dump($producto);
                    break;
                }
            }

            $productosJSON = json_encode($productos, JSON_PRETTY_PRINT);
            file_put_contents($json_file, $productosJSON);
        }

        /**
         * Esta funcion lee un archivo json de pizzas.
         * 
         * @param string la ubicacion del archivo .json
         * @return array retornara un array vacio sino pudo cargar,
         * o retorna el array cargado.
         */
        public static function LeerJSON($jsonFile){
            $pizzas = array();
            if(file_exists($jsonFile)){
                $archivo = fopen($jsonFile, "r");
                if ($archivo) {
                    $fileSize = filesize($jsonFile);
                    if ($fileSize > 0) {
                        $json = fread($archivo, $fileSize);
                        $pizzasJson = json_decode($json, true);
                        foreach ($pizzasJson as $pizza) {
                            array_push($pizzas, new Pizza(
                                intval($pizza["_id"]), 
                                $pizza["_sabor"], 
                                floatval($pizza["_precio"]), 
                                $pizza["_tipo"], 
                                intval($pizza["_cantidad"]),
                                $pizza["_imagen"],
                            ));
                        }
                    }
                    fclose($archivo);
                }
            }
            return $pizzas;
        }

        /**
         * Me permitira guardar el array de pizzas en el archivo
         * json.
         * @param array el array de pizzas.
         * @param string ruta del archivo
         * @return bool true si pudo false sino.
         */
        public static function GuardarJSON($pizzas, $jsonFilename){
            $success = false;
            try {
                $file = fopen($jsonFilename, "w");
                if ($file) {
                    $json = json_encode($pizzas, JSON_PRETTY_PRINT);
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