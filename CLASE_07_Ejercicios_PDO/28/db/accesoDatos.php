<?php

    class AccesoDatos{
//********************************************** ATRIBUTOS *************************************************************
        private static $_objAccesoDB;
        private $_objPDO;
//********************************************** CONSTRUCTOR *************************************************************
        public function __construct()
        {
            try{
                $this->_objPDO = new PDO('mysql:host=localhost;dbname=clase_07;charset=utf8', 'root', '', array(PDO::ATTR_EMULATE_PREPARES => false,PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
                $this->_objPDO->exec("SET CHARACTER SET utf8");
            }
            catch(PDOException $ex){
                print "ERROR!: " . $ex->getMessage();
                die();
            }
        }
//********************************************** FUNCIONES *************************************************************
        /**
         * Me permitira retornar una consulta PDO.
         */
        public function retornarConsulta($sql)
        { 
            return $this->_objPDO->prepare($sql); 
        }

        /**
         * Me permtiria retornar el ultimo
         * id del usuario insertado
         */
        public function retornarUltimoUsuarioInsertado()
        { 
            return $this->_objPDO->lastInsertId(); //-->Metodo de PDO
        }
        
        public static function obtenerObjetoAcceso()
        { 
            if (!isset(self::$_objAccesoDB)) {          
                self::$_objAccesoDB = new AccesoDatos(); 
            } 
            return self::$_objAccesoDB;        
        }

        public function __clone()
        { 
            trigger_error('La clonación de este objeto no está permitida', E_USER_ERROR); 
        }
    }