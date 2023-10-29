<?php
    require_once "./31/classes/usuario.php";
    require_once "./31/classes/Producto.php";
    require_once "./31/db/accesoDatos.php";


    class Venta{
//********************************************* ATRIBUTOS *********************************************
        private $_idVenta;
        private $_codBarra;
        private $_idUsuario;
        private $_fechaRegistro;
        private $_cantidad;
//********************************************* SETTERS *********************************************
        public function setID($id){
            if (isset($id) && is_numeric($id)){
                $this->_idVenta = $id;
            }
        }
        public function setCodBarra($codBarra){
            if (isset($codBarra) && is_numeric($codBarra)){
                $this->_codBarra = $codBarra;
            }
        }
        public function setIDUsuario($idUsuario){
            if (isset($idUsuario) && is_numeric($idUsuario)){
                $this->_idUsuario = $idUsuario;
            }
        }
        public function setFechaRegistro($fechaRegistro){
            if ($fechaRegistro instanceof DateTime) {
                $this->_fechaRegistro = $fechaRegistro->format('Y-m-d H:i:s');
            }
        }
        public function setCantidad($cantidad){
            if (isset($cantidad) && is_numeric($cantidad)){
                $this->_cantidad = $cantidad;
            }
        }
//********************************************* GETTERS *********************************************
        public function getID(){
            return $this->_idVenta;
        }
        public function getCodBarra(){
            return $this->_codBarra;
        }
        public function getIDUsuario(){
            return $this->_idUsuario;
        }
        public function getCantidad(){
            return $this->_cantidad;
        }
        public function getFechaRegistro(){
            return new DateTime($this->_fechaRegistro);
        }
//********************************************* CONSTRUCTOR *********************************************
        public function __construct($codBarra,$idUsuario,$cantidad) {
            $this->setCodBarra($codBarra);
            $this->setIDUsuario($idUsuario);
            $this->setCantidad($cantidad);
            $this->setFechaRegistro(new DateTime());
        }
//********************************************* FUNCIONES *********************************************
        /**
         * Me permitira registrar una instancia
         * de la clase Venta en la tabla ventas.
         * 
         * @return bool true si pudo, false sino.
         */
        public function registrarVenta(){
            $objAcceso = AccesoDatos::obtenerObjetoAcceso();
            $fechaRegistro = $this->getFechaRegistro()->format('Y-m-d H:i:s'); // Formatear la fecha

            $consulta = $objAcceso->RetornarConsulta("INSERT INTO ventas (codBarra,idUsuario,cantidad,fechaRegistro) values (:codBarra,:idUsuario,:cantidad,:fechaRegistro)");
            $consulta->bindValue(':codBarra', $this->getCodBarra(), PDO::PARAM_INT);
            $consulta->bindValue(':idUsuario', $this->getIDUsuario(), PDO::PARAM_INT);
            $consulta->bindValue(':cantidad', $this->getCantidad(), PDO::PARAM_INT);
            $consulta->bindValue(':fechaRegistro', $fechaRegistro, PDO::PARAM_STR); // Usar la fecha formateada

            $consulta->execute();
            return $objAcceso->retornarUltimaVentaInsertada();
        }

        /**
         * Me permtiria realizar una venta si
         * existe el usuario, el producto y hay
         * stock suficiente.
         * 
         * @return bool true si todo salio bien,
         * false sino.
         */
        public function realizarVenta(){
            $usuario = Usuario::buscarUsuario($this->getIDUsuario());
            // var_dump($usuario);
            $producto = Producto::buscarProductoPorID($this->getCodBarra());
            // var_dump($producto);

            if ($usuario !== null && $producto !== null) {
                if (Producto::verificarStock($producto, $this->getCantidad())) {
                    if (Producto::actualizarStock($producto, $this->getCantidad(), "-") && $this->registrarVenta()) {
                        echo 'Venta generada correctamente!';
                        return true;
                    }
                } else {
                    echo 'No hay stock suficiente para realizar la venta!<br>';
                }
            }            
            return false;
        }
    }