<?php

    include_once "Cupon.php";

    class Devolucion{
//********************************************* ATRIBUTOS *********************************************
        public $_id;
        public $_numeroPedido;
        public $_causaDevolucion;        
//********************************************* GETTERS *********************************************
        public function getId(){
            return $this->_id;
        }    
        public function getNumeroPedido(){
            return $this->_numeroPedido;
        }
        public function getCausaDevolucion(){
            return $this->_causaDevolucion;
        }
//********************************************* SETTERS *********************************************
        public function setID($id){
            if (isset($id) && is_numeric($id)){
                $this->_id = $id;
            }
        }
        public function setNumeroPedido($numero){
            if (isset($numero) && is_numeric($numero)){
                $this->_numeroPedido = $numero;
            }
        }
        public function setCausaDevolucion($causa){
            if(isset($causa) && !empty($causa)) {
                $this->_causaDevolucion = $causa;
            }
        }
//********************************************* CONSTRUCTOR *********************************************
        public function __construct($id,$numeroPedido,$causaDevolucion){
            $this->setID($id);
            $this->setNumeroPedido($numeroPedido);
            $this->setCausaDevolucion($causaDevolucion);
        }
//********************************************* FUNCIONES *********************************************

        /**
         * Me genera una nueva devolucion y la guarda en el archiv
         * json que recibe.
         */
        public static function generarDevolucion($devolucion,$jsonFileDevoluciones){
            $devoluciones = Devolucion::leerJSON($jsonFileDevoluciones);
            array_push($devoluciones,$devolucion);
            return self::guardarJSON($devoluciones,$jsonFileDevoluciones);
        }

        /**
         * Me permitira listar devoluciones con cupones
         * en formato <ul> <li></li> <ul>
         * devolviendo la cadena.
         * 
         * @param array $devolucionesConCupones el array a 
         * imprimir.
         * @return string la string a imprimir.
         */
        public static function listarDevolucionesConCupones($devolucionesConCuponesYEstados) {
            foreach ($devolucionesConCuponesYEstados as $item) {
                echo 'Devolución ID: ' . $item['devolucion']->getId() . '<br>';
                echo 'Cupón ID: ' . $item['cupon']->getID() . '<br>';
                echo 'Estado del Cupón: ' . ($item['usado'] ? 'Usado' : 'No Usado') . '<br>';
                echo '<hr>';
            }
        }

        public static function obtenerDevolucionesConCuponesYEstados($devoluciones, $cupones) {
            $devolucionesConCuponesYEstados = [];
        
            foreach ($devoluciones as $devolucion) {
                $cuponID = $devolucion->getNumeroPedido();
                // Buscar el cupón correspondiente
                $cupon = null;
                foreach ($cupones as $c) {
                    var_dump($c->getNumeroPedidio());
                    if ($c->getNumeroPedido() == $cuponID) {
                        $cupon = $c;
                        break;
                    }
                }
        
                if ($cupon !== null) {
                    $cuponUsado = $cupon->getEstado();
        
                    // Agregar la devolución junto con el cupón y su estado de uso a la lista
                    $devolucionConCuponYEstado = [
                        'devolucion' => $devolucion,
                        'cupon' => $cupon,
                        'usado' => $cuponUsado,
                    ];
        
                    $devolucionesConCuponesYEstados[] = $devolucionConCuponYEstado;
                }
            }
        
            return $devolucionesConCuponesYEstados;
        }
        

        /**
         * Esta funcion lee un archivo json de devoluciones.
         * 
         * @param string la ubicacion del archivo .json
         * @return array retornara un array vacio sino pudo cargar,
         * o retorna el array cargado.
         */
        public static function leerJSON($jsonFile){
            $devoluciones = array();
            if(file_exists($jsonFile)){
                $archivo = fopen($jsonFile, "r");
                if ($archivo) {
                    $fileSize = filesize($jsonFile);
                    if ($fileSize > 0) {
                        $json = fread($archivo, $fileSize);
                        $devolucionesJson = json_decode($json, true);
                        foreach ($devolucionesJson as $devolucion) {
                            array_push($devoluciones, new Devolucion(
                                intval($devolucion["_id"]), 
                                intval($devolucion["_numeroPedido"]),
                                $devolucion["_causaDevolucion"],  
                            ));
                        }
                    }
                    fclose($archivo);
                }
            }
            return $devoluciones;
        }

        /**
         * Me permitira guardar el array de productos en el archivo
         * json.
         * @param array el array de productos.
         * @param string ruta del archivo
         * @return bool true si pudo false sino.
         */
        public static function guardarJSON($devoluciones, $jsonFilename){
            $success = false;
            try {
                $file = fopen($jsonFilename, "w");
                if ($file) {
                    $json = json_encode($devoluciones, JSON_PRETTY_PRINT);
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