<?php
    require_once "./31/db/accesoDatos.php";

    class Producto{
//********************************************* ATRIBUTOS *********************************************
        private $_id;
        private $_nombre;
        private $_precio;
        private $_tipo;
        private $_stock; 
        private $_fechaRegistro;
//********************************************* PROPIEDADES SETTERS *********************************************
        public function setID($id){
            if (isset($id) && is_numeric($id)){
                $this->_id = $id;
            }
        }
        public function setNombre($nombre){
            if(isset($nombre) && !empty($nombre)) {
                $this->_nombre = $nombre;
            }
        }
        public function setPrecio($precio){
            if(isset($precio)){
                $this->_precio = $precio;
            }
        }
        public function setTipo($tipo){
            if (isset($tipo)){
                $this->_tipo = $tipo;
            }
        }
        public function setStock($stock){
            if (!empty($stock) && is_numeric($stock)){
                $this->_stock = $stock;
            }
        }
        public function setFechaRegistro($fechaRegistro){
            if ($fechaRegistro instanceof DateTime) {
                $this->_fechaRegistro = $fechaRegistro->format('Y-m-d H:i:s');
            }
        }
//********************************************* PROPIEDADES GETTERS *********************************************
        public function getID(){
            return $this->_id;
        }
        public function getNombre(){
            return $this->_nombre;
        }
        public function getPrecio(){
            return $this->_precio;
        }
        public function getTipo(){
            return $this->_tipo;
        }
        public function getStock(){
            return $this->_stock;
        }
        public function getFechaRegistro(){
            return new DateTime($this->_fechaRegistro);
        }
//********************************************* CONSTRUCTOR *********************************************
        public function __construct($nombre, $precio, $tipo, $stock){
            // $this->setID($id);
            $this->setNombre($nombre);
            $this->setPrecio($precio);
            $this->setTipo($tipo);
            $this->setStock($stock);
            $this->setFechaRegistro(new DateTime());
        }
//********************************************* FUNCIONES *********************************************
        /**
         * Me permitira buscar si existe o no un producto 
         * dentro de la tabla.
         * 
         * @param string $nombre el nombre del producto
         * @param string $tipo el tipo del producto.
         * 
         * @return Producto||null el producto o null sino 
         * existe.
         */
        public static function buscarProducto($nombre, $tipo) {
            $objAccessoDatos = AccesoDatos::obtenerObjetoAcceso();
            $consulta = $objAccessoDatos->RetornarConsulta("SELECT * FROM productos WHERE nombre = :nombre AND tipo = :tipo");
            $consulta->bindValue(':nombre', $nombre, PDO::PARAM_STR);
            $consulta->bindValue(':tipo', $tipo, PDO::PARAM_STR);
            $consulta->execute();
            
            return $consulta->fetch(PDO::FETCH_ASSOC) ?: null;
        }

        public static function buscarProductoPorID($codBarra) {
            $objAccessoDatos = AccesoDatos::obtenerObjetoAcceso();
            $consulta = $objAccessoDatos->RetornarConsulta("SELECT * FROM productos WHERE id = :id");
            $consulta->bindValue(':id', $codBarra, PDO::PARAM_INT);
            $consulta->execute();
        
            return $consulta->fetch(PDO::FETCH_ASSOC) ?: null;
        }

        /**
         * Me permitira saber si la instancia tiene
         * el stock suficiente.
        */
        public static function verificarStock($productoData, $cantidad){
            return $productoData['stock'] > $cantidad;
        }
        

        /**
         * Me permitira registrar una instancia de un nuevo producto
         * parametrizado en el tabla.
         * 
         * @return bool true si pudo, false sino.
         */
        public function registrarProductoParametrizado(){
            $objAccessoDatos = AccesoDatos::obtenerObjetoAcceso();
            $consulta = $objAccessoDatos->RetornarConsulta("INSERT INTO productos (nombre,tipo,stock,precio,fechaRegistro)values(:nombre,:tipo,:stock,:precio,:fechaRegistro)");
            $consulta->bindValue(':nombre', $this->getNombre(), PDO::PARAM_STR);
            $consulta->bindValue(':precio', $this->getPrecio(), PDO::PARAM_INT);
            $consulta->bindValue(':tipo', $this->getTipo(), PDO::PARAM_STR);
            $consulta->bindValue(':stock', $this->getStock(), PDO::PARAM_INT);
            $consulta->bindValue(':fechaRegistro', $this->_fechaRegistro, PDO::PARAM_STR);
            $consulta->execute();
            return $objAccessoDatos->retornarUltimoProductoInsertado();
        }

        /**
         * Funcion que me permitira actualizar el stock
         * de un producto en la tabla productos.
         * 
         * @param Producto $productoExistente el producto
         * a actualizar.
         * @param int $nuevoStock el nuevo stock a agregar.
         * 
         * @return bool true si pudo actualizar, false sino.
         */
        public static function actualizarStock($productoExistente, $nuevoStock, $signo) {
            $objAccessoDatos = AccesoDatos::obtenerObjetoAcceso();
            $consulta = $objAccessoDatos->RetornarConsulta("UPDATE productos SET stock = stock " . $signo . ":nuevoStock WHERE id = :id");
            $consulta->bindValue(':nuevoStock', $nuevoStock, PDO::PARAM_INT);
            $consulta->bindValue(':id', $productoExistente['id'], PDO::PARAM_INT);
            $consulta->execute();
            return $consulta->rowCount() > 0;
        }
        
        
        /**
         * Funcion intermediaria que me permitira
         * verificar si primero existe el producto,
         * si existe actualiza su stock, sino 
         * intenta ingresarlo.
         * 
         * @return bool true si todo salio correctamente,
         * false sino.
         */
        public function registrarOActualizarProducto() {  
            $productoExistente = self::buscarProducto($this->getNombre(), $this->getTipo());
            // var_dump($productoExistente);
        
            if ($productoExistente !== false) { 
                if(self::actualizarStock($productoExistente, $this->getStock(),"+")){//-->Que sume al stock
                    echo 'Producto actualizado<br>';
                    return true;
                }
                return false;
            } else {  
                if($this->registrarProductoParametrizado()){
                    echo 'Producto ingresado correctamente';
                    return true;
                }
                return false;
            }
            return false;
        }
        
    }