<?php 

    include_once "./28/db/accesoDatos.php";

    class Usuario{
//********************************************** ATRIBUTOS *************************************************************
        private $_idUsuario;
        private $_nombre;
        private $_apellido;
        private $_clave;
        private $_mail;
        private $_localidad;
        private $_fechaRegistro;
//********************************************** PROPIEDADES GETTERS *************************************************************
        public function getID(){
            return $this->_idUsuario;
        }
        public function getNombre(){
            return $this->_nombre;
        }
        public function getApellido(){
            return $this-> _apellido;
        }
        public function getClave(){
            return $this->_clave;
        }
        public function getMail(){
            return $this->_mail;
        }
        public function getLocalidad(){
            return $this->_localidad;
        }
        public function getFechaRegistro(){
            return $this->_fechaRegistro;
        }
//********************************************** PROPIEDADES SETTERS *************************************************************
        public function setID($id){
            if(isset($id) && is_numeric($id)){
                $this->_idUsuario = $id;
            }
        }
        public function setNombre($nombre){
            if(isset($nombre)){
                $this->_nombre= $nombre;
            }
        }
        public function setApellido($apellido){
            if(isset($apellido)){
                $this->_apellido=$apellido;
            }
        }
        public function setClave($clave){
            if(isset($clave)){
                $this->_clave=$clave;
            }
        }
        public function setMail($mail){
            if(isset($mail)){
                $this->_mail = $mail;
            }
        }
        public function setLocalidad($localidad){
            if(isset($localidad)){
                $this->_localidad =$localidad;
            }
        }
        public function setFechaRegistro($fecha){
            if ($fecha instanceof DateTime) {
                $this->_fechaRegistro = $fecha->format('Y-m-d H:i:s');
            }
        }
//********************************************** CONSTRUCTOR *************************************************************

//********************************************** FUNCIONES *************************************************************
        /**
         * Funcion que me permitira registrar un usuario
         * en la tabla de usuario en la db.
         * 
         * @return int el id del ultimo usuario.
         */
        public function registrarUsuarioParametros(){
            $objAccesoDatos = AccesoDatos::obtenerObjetoAcceso();
            $consulta = $objAccesoDatos->RetornarConsulta("INSERT into usuarios (nombre,apellido,clave,email,localidad,fechaRegistro)values(:nombre,:apellido,:clave,:email,:localidad,:fechaRegistro)");
            $consulta->bindValue(':nombre', $this->_nombre, PDO::PARAM_STR);
            $consulta->bindValue(':apellido', $this->_apellido, PDO::PARAM_STR);
            $consulta->bindValue(':clave', $this->_clave, PDO::PARAM_STR);
            $consulta->bindValue(':email', $this->_mail, PDO::PARAM_STR);
            $consulta->bindValue(':localidad', $this->_localidad, PDO::PARAM_STR);
            $consulta->bindValue(':fechaRegistro', $this->_fechaRegistro, PDO::PARAM_STR);
            $consulta->execute();
            return $objAccesoDatos->retornarUltimoUsuarioInsertado();
        }

        /**
         * Me permitira mostrar los datos del usuario.
         * @return string la cadena con los datos del
         * usuario.
         */
        public function mostrarUsuario(){
            return "Usuario: " . $this->getNombre() . $this->getApellido() . $this->getMail() . $this->getClave() . $this->getLocalidad() . $this->getFechaRegistro();
        }

        public function registrarUsuario(){
            $objetoAccesoDato = AccesoDatos::obtenerObjetoAcceso();
            $consulta = $objetoAccesoDato->retornarConsulta(("INSERT into usuarios (nombre,apellido,clave,email,localidad,fechaRegistro)values('$this->_nombre','$this->_apellido','$this->_clave','$this->_mail','$this->_localidad',$this->_fechaRegistro)"));
            $consulta->execute();
            return $objetoAccesoDato->retornarUltimoUsuarioInsertado();
        }

        /**
         * Me permitira traerme a todos
         * los usuarios de la db.
         */
        public static function traerTodosLosUsuarios(){
            $objetoAccesoDato = AccesoDatos::obtenerObjetoAcceso();
            $consulta = $objetoAccesoDato->retornarConsulta("select id,nombre,apellido,clave,email,localidad,fechaRegistro from usuarios");
            $consulta->execute();
            return $consulta->fetchAll(PDO::FETCH_CLASS, "usuario");
        }

        /**
         * Me permite verificar si existe o no un
         * usuario mediante su mail
         * y clave.
         * 
         * @param string $mail el mail del usuario.
         * @param string $clave la clave del usuario
         * 
         * @param string el resultado.
         */
        public static function verificarUsuario($mail, $clave) {
            $objetoAccesoDato = AccesoDatos::obtenerObjetoAcceso();
            $consulta = $objetoAccesoDato->retornarConsulta("SELECT id FROM usuarios WHERE email = :mail AND clave = :clave");
            $consulta->bindValue(':mail', $mail, PDO::PARAM_STR);
            $consulta->bindValue(':clave', $clave, PDO::PARAM_STR);
            $consulta->execute();
        
            if ($consulta->rowCount() > 0) {
                return "Verificado";  //-->Quire decir que existe
            } elseif (!$consulta->rowCount() && self::existeUsuarioPorMail($mail)) {
                return "Error en los datos";//-->Falla algo en los datos ingresados
            } else {
                return "Usuario no registrado"; //-->Directamente no esta registrado
            }
        }

        /**
         * Me permite fijarme si existe un usuario
         * por email.
         * 
         * @return bool true si existe, false sino.
         */
        public static function existeUsuarioPorMail($mail) {
            $objetoAccesoDato = AccesoDatos::obtenerObjetoAcceso();
            $consulta = $objetoAccesoDato->retornarConsulta("SELECT id FROM usuarios WHERE email = :mail");
            $consulta->bindValue(':mail', $mail, PDO::PARAM_STR);
            $consulta->execute();
        
            return $consulta->rowCount() > 0;
        }
        
        
}